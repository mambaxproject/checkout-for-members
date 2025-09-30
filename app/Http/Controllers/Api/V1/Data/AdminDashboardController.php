<?php

namespace App\Http\Controllers\Api\V1\Data;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shop;
use App\Services\GoogleAnalyticsService\GoogleAnalyticsCheckoutService;
use App\Services\GoogleAnalyticsService\GoogleAnalyticsSalesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class AdminDashboardController extends Controller
{
    public function getDashboardData(Request $request)
    {
        $start_date = $request->input('filter.start_date') ?? now()->subDays(7)->format('Y-m-d');
        $end_date   = $request->input('filter.end_date') ?? now()->format('Y-m-d');
        $allByUser  = $request->input('filter.allByUser');

        if ($request->has('filter.start_date') && is_null($request->input('filter.start_date'))) {
            $start_date = null;
        }

        if ($request->has('filter.end_date') && is_null($request->input('filter.end_date'))) {
            $end_date = null;
        }

        $request->merge([
            'filter' => compact('start_date', 'end_date', 'allByUser'),
        ]);

        $totalOrdersCount = QueryBuilder::for(Order::class)
            ->whereHas('payments', fn ($query) => $query->whereIn('payment_status', [
                ...Order::$statusForPending,
                ...Order::$statusForCanceled,
                ...Order::$statusForPaid,
            ]))
            ->allowedFilters([
                AllowedFilter::scope('allByUser', 'allByUser'),
                AllowedFilter::callback('start_date', fn ($query, $value) => $query->whereDate('orders.created_at', '>=', $value)),
                AllowedFilter::callback('end_date', fn ($query, $value) => $query->whereDate('orders.created_at', '<=', $value)),
            ])
            ->count();

        $totalPaidOrdersCount = QueryBuilder::for(Order::class)
            ->whereHas('payments', fn ($query) => $query->whereIn('payment_status', Order::$statusForPaid))
            ->allowedFilters([
                AllowedFilter::scope('allByUser', 'allByUser'),
                AllowedFilter::callback('start_date', fn ($query, $value) => $query->whereDate('orders.created_at', '>=', $value)),
                AllowedFilter::callback('end_date', fn ($query, $value) => $query->whereDate('orders.created_at', '<=', $value)),
            ])
            ->count();

        $totalPendingOrdersCount = QueryBuilder::for(Order::class)
            ->whereHas('payments', fn ($query) => $query->whereIn('payment_status', Order::$statusForPending))
            ->allowedFilters([
                AllowedFilter::scope('allByUser', 'allByUser'),
                AllowedFilter::callback('start_date', fn ($query, $value) => $query->whereDate('orders.created_at', '>=', $value)),
                AllowedFilter::callback('end_date', fn ($query, $value) => $query->whereDate('orders.created_at', '<=', $value)),
            ])
            ->count();

        $totalCanceledOrdersCount = QueryBuilder::for(Order::class)
            ->whereHas('payments', fn ($query) => $query->whereIn('payment_status', Order::$statusForCanceled))
            ->allowedFilters([
                AllowedFilter::scope('allByUser', 'allByUser'),
                AllowedFilter::callback('start_date', fn ($query, $value) => $query->whereDate('orders.created_at', '>=', $value)),
                AllowedFilter::callback('end_date', fn ($query, $value) => $query->whereDate('orders.created_at', '<=', $value)),
            ])
            ->count();

        $ordersSumData['total_sum'] = QueryBuilder::for(Order::class)
            ->allowedFilters([
                AllowedFilter::scope('allByUser', 'allByUser'),
                AllowedFilter::callback('start_date', fn ($query, $value) => $query->whereDate('orders.created_at', '>=', $value)),
                AllowedFilter::callback('end_date', fn ($query, $value) => $query->whereDate('orders.created_at', '<=', $value)),
            ])
            ->sum('net_amount') ?? 0;

        $ordersSumData['total_paid_sum'] = QueryBuilder::for(Order::class)
            ->whereHas('payments', fn ($query) => $query->whereIn('payment_status', Order::$statusForPaid))
            ->allowedFilters([
                AllowedFilter::scope('allByUser', 'allByUser'),
                AllowedFilter::callback('start_date', fn ($query, $value) => $query->whereDate('orders.created_at', '>=', $value)),
                AllowedFilter::callback('end_date', fn ($query, $value) => $query->whereDate('orders.created_at', '<=', $value)),
            ])
            ->sum('net_amount') ?? 0;

        $ordersSumData['total_pending_sum'] = QueryBuilder::for(Order::class)
            ->whereHas('payments', fn ($query) => $query->whereIn('payment_status', Order::$statusForPending))
            ->allowedFilters([
                AllowedFilter::scope('allByUser', 'allByUser'),
                AllowedFilter::callback('start_date', fn ($query, $value) => $query->whereDate('orders.created_at', '>=', $value)),
                AllowedFilter::callback('end_date', fn ($query, $value) => $query->whereDate('orders.created_at', '<=', $value)),
            ])
            ->sum('net_amount') ?? 0;

        $ordersSumData['total_canceled_sum'] = QueryBuilder::for(Order::class)
            ->whereHas('payments', fn ($query) => $query->whereIn('payment_status', Order::$statusForCanceled))
            ->allowedFilters([
                AllowedFilter::scope('allByUser', 'allByUser'),
                AllowedFilter::callback('start_date', fn ($query, $value) => $query->whereDate('orders.created_at', '>=', $value)),
                AllowedFilter::callback('end_date', fn ($query, $value) => $query->whereDate('orders.created_at', '<=', $value)),
            ])
            ->sum('net_amount') ?? 0;

        $ordersPerDay = QueryBuilder::for(Order::class)
            ->selectRaw("
                DATE(orders.created_at) AS date, 
                DATE_FORMAT(orders.created_at, '%d/%m') AS day, 
                COUNT(orders.id) AS quantity_orders, 
                SUM(orders.net_amount) AS total_net_amount
            ")
            ->allowedFilters([
                AllowedFilter::scope('allByUser', 'allByUser'),
                AllowedFilter::callback('start_date', fn ($query, $value) => $query->whereDate('orders.created_at', '>=', $value)),
                AllowedFilter::callback('end_date', fn ($query, $value) => $query->whereDate('orders.created_at', '<=', $value)),
            ])
            ->groupBy('day')
            ->toBase()
            ->get();

        $infosOrdersByPaymentMethod = QueryBuilder::for(Order::class)
            ->selectRaw('
                order_payments.payment_method AS payment_method,
                COUNT(orders.id) AS quantity_orders, 
                COALESCE(SUM(orders.net_amount), 0) AS total_net_amount,
                COALESCE(SUM(CASE 
                    WHEN order_payments.payment_status IN (' . implode(',', array_map(fn ($s) => \DB::getPdo()->quote(strtoupper($s)), Order::$statusForPaid)) . ') 
                    THEN orders.net_amount 
                    ELSE 0 
                END), 0) AS total_paid_amount,
                COALESCE(ROUND(
                    100.0 * SUM(CASE
                        WHEN order_payments.payment_status IN (' . implode(',', array_map(fn ($s) => \DB::getPdo()->quote(strtoupper($s)), Order::$statusForPaid)) . ')
                        THEN 1 ELSE 0
                    END) / COUNT(orders.id), 2
                ), 0) AS conversion_rate
            ')
            ->join('order_payments', 'orders.id', '=', 'order_payments.order_id')
            ->whereNotIn(
                'order_payments.payment_status',
                array_map(fn ($s) => strtoupper($s), Order::$statusForFailed)
            )
            ->allowedFilters([
                AllowedFilter::scope('allByUser', 'allByUser'),
                AllowedFilter::callback('start_date', fn ($query, $value) => $query->whereDate('orders.created_at', '>=', $value)),
                AllowedFilter::callback('end_date', fn ($query, $value) => $query->whereDate('orders.created_at', '<=', $value)),
            ])
            ->groupBy(['order_payments.payment_method'])
            ->toBase()
            ->get()
            ->keyBy('payment_method')
            ->union(collect([
                'PIX'         => (object) ['payment_method' => 'PIX', 'quantity_orders' => 0, 'total_net_amount' => 0, 'total_paid_amount' => 0, 'conversion_rate' => 0],
                'BILLET'      => (object) ['payment_method' => 'BILLET', 'quantity_orders' => 0, 'total_net_amount' => 0, 'total_paid_amount' => 0, 'conversion_rate' => 0],
                'CREDIT_CARD' => (object) ['payment_method' => 'CREDIT_CARD', 'quantity_orders' => 0, 'total_net_amount' => 0, 'total_paid_amount' => 0, 'conversion_rate' => 0],
            ]))
            ->mapWithKeys(fn ($item, $key) => [$key => (object) $item]);

        $ordersPerDayWithPaymentMethod = QueryBuilder::for(Order::class)
            ->join('order_payments', 'orders.id', '=', 'order_payments.order_id')
            ->selectRaw("
                DATE(orders.created_at) AS date, 
                DATE_FORMAT(orders.created_at, '%d/%m') AS day, 
                order_payments.payment_method AS payment_method,
                CASE 
                    WHEN order_payments.payment_method = 'CREDIT_CARD' THEN 'Cartão de Crédito'
                    WHEN order_payments.payment_method = 'BILLET' THEN 'Boleto'
                    WHEN order_payments.payment_method = 'PIX' THEN 'PIX'
                    ELSE 'Outro'
                END AS payment_method_translated,
                COUNT(orders.id) AS quantity_orders, 
                SUM(orders.net_amount) AS total_net_amount
            ")
            ->allowedFilters([
                AllowedFilter::scope('allByUser', 'allByUser'),
                AllowedFilter::callback('start_date', fn ($query, $value) => $query->whereDate('orders.created_at', '>=', $value)),
                AllowedFilter::callback('end_date', fn ($query, $value) => $query->whereDate('orders.created_at', '<=', $value)),
            ])
            ->groupBy('day', 'payment_method')
            ->toBase()
            ->get();

        $topSellingProducts = QueryBuilder::for(Order::class)
            ->with(['item.product.shop.owner'])
            ->selectRaw('
                orders.id,
                COUNT(orders.id) AS quantity_orders, 
                SUM(orders.net_amount) AS total_amount,
                COALESCE(parent_products.name, products.name) AS product_name
            ')
            ->whereHas('payments', fn ($query) => $query->whereIn('payment_status', Order::$statusForPaid))
            ->allowedFilters([
                AllowedFilter::scope('allByUser', 'allByUser'),
                AllowedFilter::callback('start_date', fn ($query, $value) => $query->whereDate('orders.created_at', '>=', $value)),
                AllowedFilter::callback('end_date', fn ($query, $value) => $query->whereDate('orders.created_at', '<=', $value)),
            ])
            ->join('item_orders', 'orders.id', '=', 'item_orders.order_id')
            ->join('products', 'item_orders.product_id', '=', 'products.id')
            ->leftJoin('products as parent_products', 'products.parent_id', '=', 'parent_products.id')
            ->groupBy('products.name')
            ->orderByDesc('total_amount')
            ->limit(5)
            ->get();

        $topAffiliates = QueryBuilder::for(Order::class)
            ->selectRaw('
                COUNT(orders.id) AS quantity_orders, 
                SUM(orders.net_amount) AS total_amount,
                users.name AS affiliate_name
            ')
            ->whereHas('payments', fn ($query) => $query->whereIn('payment_status', Order::$statusForPaid))
            ->allowedFilters([
                AllowedFilter::scope('allByUser', 'allAffiliatesByUser'),
                AllowedFilter::callback('start_date', fn ($query, $value) => $query->whereDate('orders.created_at', '>=', $value)),
                AllowedFilter::callback('end_date', fn ($query, $value) => $query->whereDate('orders.created_at', '<=', $value)),
            ])
            ->join('affiliates', 'orders.affiliate_id', '=', 'affiliates.id')
            ->join('users', 'affiliates.user_id', '=', 'users.id')
            ->groupBy('users.name')
            ->orderByDesc('quantity_orders')
            ->limit(5)
            ->get();

        $quantitiesSubscriptionsPerSituation = QueryBuilder::for(Order::class)
            ->selectRaw('
                -- Novos Assinantes
                COUNT(DISTINCT CASE 
                    WHEN MONTH(orders.created_at) = MONTH(CURRENT_DATE) 
                         AND YEAR(orders.created_at) = YEAR(CURRENT_DATE) 
                         AND EXISTS (
                            SELECT 1 FROM order_payments 
                            WHERE order_payments.order_id = orders.id 
                              AND order_payments.payment_status IN (' . implode(',', array_map(fn ($s) => \DB::getPdo()->quote($s), Order::$statusForPaid)) . ')
                         ) 
                    THEN orders.user_id ELSE NULL END
                ) as new_subscribers,
        
                -- Assinantes Mantidos
                COUNT(DISTINCT CASE 
                    WHEN (SELECT COUNT(*) 
                          FROM orders o2 
                          JOIN order_payments op2 ON op2.order_id = o2.id 
                          WHERE o2.user_id = orders.user_id 
                            AND op2.payment_status IN (' . implode(',', array_map(fn ($s) => \DB::getPdo()->quote($s), Order::$statusForPaid)) . ')
                         ) > 1 
                    THEN orders.user_id ELSE NULL END
                ) as maintained_subscribers,
        
                -- Cancelamentos
                COUNT(DISTINCT CASE 
                    WHEN EXISTS (
                        SELECT 1 FROM order_payments 
                        WHERE order_payments.order_id = orders.id 
                          AND order_payments.payment_status IN (' . implode(',', array_map(fn ($s) => \DB::getPdo()->quote($s), Order::$statusForCanceled)) . ')
                    ) 
                    THEN orders.user_id ELSE NULL END
                ) as cancellations
            ')
            ->isSubscription()
            ->allowedFilters([
                AllowedFilter::scope('allByUser', 'allByUser'),
                AllowedFilter::callback('start_date', fn ($query, $value) => $query->whereDate('orders.created_at', '>=', $value)),
                AllowedFilter::callback('end_date', fn ($query, $value) => $query->whereDate('orders.created_at', '<=', $value)),
            ])
            ->first();

        $shopkeepers = Shop::whereNotNull('owner_id')
            ->toBase()
            ->get(['owner_id AS id', 'name']);

        $analyticsCheckoutAccessPerDay   = [];
        $analyticsCheckoutPurchasePerDay = [];
        $analyticsBeginCheckoutPerDay    = [];
        $analyticsActiveUsers            = 0;
        $analyticsActiveUsersSales       = 0;

        try {
            $googleAnalyticsCheckoutService  = new GoogleAnalyticsCheckoutService;
            $analyticsCheckoutAccessPerDay   = $googleAnalyticsCheckoutService->checkoutUniqueUserAccessPerDay($start_date, $end_date);
            $analyticsCheckoutPurchasePerDay = $googleAnalyticsCheckoutService->checkoutPurchasePerDay($start_date, $end_date);
            $analyticsBeginCheckoutPerDay    = $googleAnalyticsCheckoutService->beginCheckoutPerDay($start_date, $end_date);
            $analyticsActiveUsers            = $googleAnalyticsCheckoutService->activeUsers();

            $googleAnalyticsSalesService = new GoogleAnalyticsSalesService;
            $analyticsActiveUsersSales   = $googleAnalyticsSalesService->activeUsers();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }

        $data = [
            'totalOrdersCount' => $totalOrdersCount,
            'totalPaidOrdersCount' => $totalPaidOrdersCount,
            'totalPendingOrdersCount' => $totalPendingOrdersCount,
            'totalCanceledOrdersCount' => $totalCanceledOrdersCount,
            'ordersSumData' => $ordersSumData,
            'ordersPerDay' => $ordersPerDay,
            'ordersPerDayWithPaymentMethod' => $ordersPerDayWithPaymentMethod,
            'topSellingProducts' => $topSellingProducts,
            'infosOrdersByPaymentMethod' => $infosOrdersByPaymentMethod,
            'topAffiliates' => $topAffiliates,
            'quantitiesSubscriptionsPerSituation' => $quantitiesSubscriptionsPerSituation,
            'analyticsActiveUsers' => $analyticsActiveUsers,
            'analyticsActiveUsersSales' => $analyticsActiveUsersSales,
            'analyticsCheckoutAccessPerDay' => $analyticsCheckoutAccessPerDay,
            'analyticsCheckoutPurchasePerDay' => $analyticsCheckoutPurchasePerDay,
            'analyticsBeginCheckoutPerDay' => $analyticsBeginCheckoutPerDay,
        ];

        return response()->json($data);
    }
}