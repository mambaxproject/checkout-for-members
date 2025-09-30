<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Subscription\SendLinkUpdateCreditCardCustomerRequest;
use App\Mail\Subscriptions\Customer\{UpdateCreditCardSubscription, UpdateOfferSubscription};
use App\Models\{Order};
use App\Services\Reports\Subscription\ReportSubscriptionService;
use App\Services\SuitPay\Endpoints\SuitpaySubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Rap2hpoutre\FastExcel\FastExcel;
use Spatie\QueryBuilder\{AllowedFilter, AllowedSort, QueryBuilder};
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubscriptionController extends Controller
{
    public function index(): View|StreamedResponse
    {
        $recurrencyIds = Order::query()
            ->isSubscription()
            ->allForUser()
            ->withWhereHas('payments', fn ($query) => $query->whereNotNull('recurrency_id'))
            ->filterByPaymentStatus('paid')
            ->get(['id'])
            ->pluck('payments.*.recurrency_id')
            ->flatten()
            ->unique()
            ->filter(fn ($id) => !is_null($id))
            ->values();

        $shop = auth()->user()->shop();

        $subscriptionService = new SuitpaySubscriptionService(
            $shop->client_id_banking,
            $shop->client_secret_banking
        );

        $subscriptionsData = collect($recurrencyIds)
            ->map(fn ($recurrencyId) => $subscriptionService->getDetails($recurrencyId))
            ->filter(fn ($details) => isset($details['recurrencyStatus'], $details['transactions']))
            ->values()
            ->all();
    
        $totalRevenue = app(ReportSubscriptionService::class)->getTotalRevenue($subscriptionsData);

        $quantityPaidSubscriptions = Order::query()
            ->isSubscription()
            ->allForUser()
            ->filterByPaymentStatus('paid')
            ->count();

        $query = QueryBuilder::for(Order::class)
            ->allForUser()
            ->isSubscription()
            ->whereHas('payments', fn ($query) => $query->whereNotIn('payment_status', Order::$statusForFailed))
            ->with(['user:id,name,phone_number,document_number', 'items.product:id,parent_id,name', 'items.product.parentProduct:id,name', 'payments'])
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
                AllowedFilter::scope('payment_status', 'FilterByPaymentStatus'),
                AllowedFilter::callback('start_at', fn ($query, $value) => $query->whereDate('created_at', '>=', $value)),
                AllowedFilter::callback('end_at', fn ($query, $value) => $query->whereDate('created_at', '<=', $value)),
            ]);

        if (request('export_file') == 'excel') {
            return $this->getExportedExcelFile($query);
        }

        $subscriptions = $query->paginate()->withQueryString();

        $user     = auth()->user()->loadMissing(['affiliates', 'coproducers']);
        $shopUser = auth()?->user()?->shop();

        return view('dashboard.subscriptions.index', compact('totalRevenue', 'quantityPaidSubscriptions', 'subscriptions', 'user', 'shopUser'));
    }

    public function show(string $orderUuid): View
    {
        $order = Order::where('client_orders_uuid', $orderUuid)->firstOrFail();
        $this->authorize('show', $order);

        $order->load(['user', 'items.product', 'payments']);

        $product      = $order->item->product->parentProduct;
        $activeOffers = $product->activeOffers($product->paymentType ?? '')->get(['id', 'name', 'price']);

        $subscriptionService = new SuitpaySubscriptionService(
            $order->shop->client_id_banking,
            $order->shop->client_secret_banking
        );

        $detailsSubscription = $subscriptionService->getDetails($order->payment->recurrencyId ?? '');

        $transactionsSubscription = collect($detailsSubscription['transactions'] ?? [])
            ->sortByDesc('transactionDate')
            ->values()
            ->all();

        $userIsAffiliateParentProductOrder = false;

        if (! is_null($order->affiliate_id) && ! $order->belongsToShop) {
            $userIsAffiliateParentProductOrder = auth()->user()->affiliates->contains($order->affiliate_id);
        }

        return view('dashboard.subscriptions.show', compact(
            'order',
            'product',
            'activeOffers',
            'detailsSubscription',
            'transactionsSubscription',
            'userIsAffiliateParentProductOrder'
        ));
    }

    public function sendLinkUpdateCreditCardCustomer(Order $order, SendLinkUpdateCreditCardCustomerRequest $request): RedirectResponse
    {
        $this->authorize('show', $order);

        \Mail::to($order->user->email)->queue(new UpdateCreditCardSubscription($order));

        $order->comment('O usuário ' . user()->name . ' enviou o link de atualização do cartão de crédito da assinatura para o cliente.');

        return back()->with('success', 'Link enviado com sucesso para o e-mail do(a) cliente.');
    }

    public function sendLinkUpdateOfferCustomer(Order $order, SendLinkUpdateCreditCardCustomerRequest $request): RedirectResponse
    {
        $this->authorize('show', $order);

        \Mail::to($order->user->email)->queue(new UpdateOfferSubscription($order, $request->input('offer_id')));

        $order->comment('O usuário ' . user()->name . ' enviou o link de atualização de oferta da assinatura para o cliente.');

        return back()->with('success', 'Link enviado com sucesso para o e-mail do(a) cliente.');
    }

    public function chargeRetry(Order $order, SendLinkUpdateCreditCardCustomerRequest $request): RedirectResponse
    {
        $this->authorize('show', $order);

        $shop = $order->shop;

        try {

            $response = (new SuitpaySubscriptionService(
                $shop->client_id_banking,
                $shop->client_secret_banking
            ))->manualRetry([
                'requestNumber' => $order->id,
                'recurrencyId'  => $order->payment->recurrencyId,
                'isNewCard'     => false,
                'callbackUrl'   => route('api.public.webhooks.suitpay.updateOrderByTransation'),
            ]);

            if ($response['status'] >= 200 && $response['status'] < 300) {

                $order->comment('A retentativa de cobrança foi efetuada com sucesso.');

                return back()->with('success', 'Nova tentativa de cobrança realizada com sucesso.');
            }

            throw new \Exception($response['message']);
        } catch (\Exception $e) {

            $msg = 'Erro ao realizar nova tentativa de cobrança: ' . $e->getMessage();

            $order->comment($msg);

            return back()->with('error', $msg);
        }
    }

    private function getExportedExcelFile(QueryBuilder $query): StreamedResponse
    {
        $user     = auth()->user()->loadMissing(['affiliates', 'coproducers']);
        $shopUser = auth()?->user()?->shop();

        return (new FastExcel($query->get()))->download('assinaturas_' . now()->format('d-m-Y') . '.xlsx', function ($item) use ($user, $shopUser) {
            return [
                'ID da assinatura'    => $item->client_orders_uuid,
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
                'Data da assinatura'  => $item->created_at->isoFormat('dddd, DD/MM/YYYY HH:mm'),
            ];
        });
    }
}
