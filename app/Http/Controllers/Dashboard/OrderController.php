<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Jobs\Dashboard\Members\DeactivateMemberJob;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\SuitPay\Endpoints\SuitpayRefundService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Number;
use Illuminate\View\View;
use Rap2hpoutre\FastExcel\FastExcel;
use Spatie\QueryBuilder\{AllowedFilter, AllowedSort, QueryBuilder};
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    public function __construct(
        public OrderService $orderService,
    ) {}

    public function index()
    {
        $query = QueryBuilder::for(Order::class)
            ->when(request()->isNotFilled('filter.type'), fn ($query) => $query->allForUser())
            ->isOrder()
            ->with(['user:id,name,email,phone_number,document_number', 'items.product.parentProduct:id,name'])
            ->withWhereHas('payments')
            ->allowedSorts([
                'id',
                'amount',
                AllowedSort::field('data', 'created_at'),
                AllowedSort::callback('payment_status', function ($query, $direction) {
                    $direction = $direction ? 'desc' : 'asc';

                    $query->join('order_payments', 'orders.id', '=', 'order_payments.order_id')
                        ->orderBy('order_payments.payment_status', $direction)
                        ->select('orders.*');
                }),
                AllowedSort::callback('payment_method', function ($query, $direction) {
                    $direction = $direction ? 'desc' : 'asc';

                    $query->join('order_payments', 'orders.id', '=', 'order_payments.order_id')
                        ->orderBy('order_payments.payment_method', $direction)
                        ->select('orders.*');
                }),
                AllowedSort::callback('customer_name', function ($query, $direction) {
                    $direction = $direction ? 'desc' : 'asc';

                    $query->join('customers', 'orders.user_id', '=', 'customers.id')
                        ->orderBy('customers.name', $direction)
                        ->select('orders.*');
                }),
                AllowedSort::callback('product_name', function ($query, $direction) {
                    $direction = $direction ? 'desc' : 'asc';

                    $query->join('item_orders', 'orders.id', '=', 'item_orders.order_id')
                        ->join('products', 'item_orders.product_id', '=', 'products.id')
                        ->join('products as parent_products', 'products.parent_id', '=', 'parent_products.id')
                        ->orderBy('parent_products.name', $direction)
                        ->select('orders.*');
                }),
            ])
            ->latest('id')
            ->allowedFilters([
                AllowedFilter::scope('user', 'filterByUser'),
                AllowedFilter::scope('type', 'fromType'),
                AllowedFilter::partial('client_orders_uuid'),
                AllowedFilter::callback('product', function ($query, $value) {
                    $query->whereHas('items.product', fn ($query) => $query->where('name', 'LIKE', "%$value%"));
                }),
                AllowedFilter::scope('payment_method', 'filterByPaymentMethod'),
                AllowedFilter::scope('payment_status', 'FilterByPaymentStatus'),
                AllowedFilter::callback('start_at', fn ($query, $value) => $query->whereDate('created_at', '>=', $value)),
                AllowedFilter::callback('end_at', fn ($query, $value) => $query->whereDate('created_at', '<=', $value)),
            ]);

        if (request('export_file') == 'excel') {
            return $this->getExportedExcelFile($query);
        }

        $orders = $query->paginate()->withQueryString();

        $averageDailyTurnover = $this->orderService->getAverageDailyTurnoverFromOrders();

        $averageDailyTurnoverLastWeek = $this->orderService->getAverageDailyTurnoverLastWeekFromOrders();

        $percentageChangeDailyTurnover = ($averageDailyTurnoverLastWeek && $averageDailyTurnoverLastWeek != 0)
            ? (($averageDailyTurnover - $averageDailyTurnoverLastWeek) / $averageDailyTurnoverLastWeek) * 100
            : (($averageDailyTurnoverLastWeek == 0 && $averageDailyTurnover > 0) ? 100 : 0);

        $percentageInfosDailyTurnover = [
            'value' => Number::percentage($percentageChangeDailyTurnover),
            'icon'  => $percentageChangeDailyTurnover > 0 ? 'arrow_upward_alt' : ($percentageChangeDailyTurnover < 0 ? 'arrow_downward_alt' : 'remove_circle_outline'),
            'class' => $percentageChangeDailyTurnover > 0 ? 'success' : ($percentageChangeDailyTurnover < 0 ? 'danger' : 'light'),
        ];

        $totalRevenue = $this->orderService->getTotalRevenueFromOrders();

        $totalRevenueLastWeek = $this->orderService->getTotalRevenueLastWeekFromOrders();

        $percentageChangeTotalRevenue = ($totalRevenueLastWeek && $totalRevenueLastWeek != 0)
            ? (($totalRevenue - $totalRevenueLastWeek) / $totalRevenueLastWeek) * 100
            : (($totalRevenueLastWeek == 0 && $totalRevenue > 0) ? 100 : 0);

        $percentageInfosTotalRevenue = [
            'value' => Number::percentage($percentageChangeTotalRevenue),
            'icon'  => $percentageChangeTotalRevenue > 0 ? 'arrow_upward_alt' : ($percentageChangeTotalRevenue < 0 ? 'arrow_downward_alt' : 'remove_circle_outline'),
            'class' => $percentageChangeTotalRevenue > 0 ? 'success' : ($percentageChangeTotalRevenue < 0 ? 'danger' : 'light'),
        ];

        $user     = auth()->user()->loadMissing(['affiliates', 'coproducers']);
        $shopUser = auth()?->user()?->shop();

        return view('dashboard.orders.index', compact(
            'orders',
            'averageDailyTurnover',
            'totalRevenue',
            'percentageInfosDailyTurnover',
            'percentageInfosTotalRevenue',
            'user',
            'shopUser',
        ));
    }

    public function show(string $orderUuid): View
    {
        $order = Order::where('client_orders_uuid', $orderUuid)->firstOrFail();
        $this->authorize('show', $order);

        $order->loadMissing(['user', 'items.product.parentProduct', 'payments']);

        $canShowCustomersData              = true;
        $userIsAffiliateParentProductOrder = false;

        if (! is_null($order->affiliate_id) && ! $order->belongsToShop) {
            $parentProduct                         = $order->items->first()?->product?->parentProduct;
            $allowAccessToCustomersDataToAffiliate = boolval($parentProduct->getValueSchemalessAttributes('affiliate.allowAccessToCustomersData'));
            $userIsAffiliateParentProductOrder     = auth()->user()->affiliates->contains($order->affiliate_id);
            $canShowCustomersData                  = $allowAccessToCustomersDataToAffiliate && $userIsAffiliateParentProductOrder;
        }

        return view('dashboard.orders.show', compact('order', 'userIsAffiliateParentProductOrder', 'canShowCustomersData'));
    }

    public function refund(Order $order): RedirectResponse
    {
        $this->authorize('show', $order);

        if (! $order->isPaid()) {
            return back()->with('info', 'O pedido não está pago, não é possível solicitar reembolso.');
        }

        if ($order->isRefunded()) {
            return back()->with('error', 'O pedido já foi reembolsado.');
        }

        $shopUser = auth()->user()->shop();

        $refundService = new SuitpayRefundService(
            $shopUser->client_id_banking,
            $shopUser->client_secret_banking
        );

        if ($order->payment->isCreditCard) {
            $resultRefund = $refundService->processRefundCreditCard($order->payment->external_identification);
        } else if ($order->payment->isPix) {
            $resultRefund = $refundService->processRefundPix(
                $order->payment->external_identification,
                'Reembolso do pedido ' . $order->id . ' solicitado pelo usuário ' . auth()->user()->name
            );
        } else {
            return back()->with('error', 'Método de pagamento não suportado para reembolso.');
        }

        if (isset($resultRefund['success']) && $resultRefund['success'] === false) {
            return back()->with('error', $resultRefund['message'] ?? 'Erro ao processar o reembolso.');
        }

        $order->payments()->update(['payment_status' => 'refunded_requested']);

        DeactivateMemberJob::dispatch($order);

        return back()->with('success', 'Solicitação de reembolso enviada com sucesso.');
    }

    private function getExportedExcelFile(QueryBuilder $query): StreamedResponse
    {
        $user     = auth()->user()->loadMissing(['affiliates', 'coproducers']);
        $shopUser = auth()?->user()?->shop();

        return (new FastExcel($query->get()))->download('pedidos_' . now()->format('d-m-Y') . '.xlsx', function ($item) use ($user, $shopUser) {
            return [
                'ID do pedido'        => $item->client_orders_uuid,
                'Nome do cliente'     => $item->user->name,
                'E-mail do cliente'   => $item->user->email,
                'Telefone do cliente' => $item->user->phone_number,
                'CPF do cliente'      => $item->user->document_number,
                'Nome do produto'     => $item->items->implode('product.parentProduct.name', ', '),
                'Nome da oferta'      => $item->items->implode('product.name', ', '),
                'Valor'               => $item->brazilianTotalAmountItems,
                'Valor líquido'       => $item->brazilianShopAmount,
                'Valor a receber'     => $item->brazilianAmountByTypeUser($user, $shopUser),
                'Método de pagamento' => $item->paymentMethod,
                'Quantidade parcelas' => $item->payments?->last()?->installments ?? 1,
                'Status de pagamento' => $item->paymentStatus,
                'Data do pedido'      => $item->created_at->isoFormat('dddd, DD/MM/YYYY HH:mm'),
            ];
        });
    }
}
