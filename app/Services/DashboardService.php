<?php

namespace App\Services;

use App\Enums\SituationProductEnum;
use App\Models\{AbandonedCart, Order};
use Illuminate\Support\{Carbon, Collection};
use Spatie\QueryBuilder\{AllowedFilter, QueryBuilder};

class DashboardService
{
    public function getTotalRevenuePaidOrders(): float
    {
        $filters = $this->handleFilters();

        return QueryBuilder::for(Order::class)
            ->fromShop()
            ->isOrder()
            ->filterByPaymentStatus('paid')
            ->allowedFilters([
                AllowedFilter::callback('start_date', fn ($query, $value) => $query->whereDate('orders.created_at', '>=', $filters['start_date'])),
                AllowedFilter::callback('end_date', fn ($query, $value) => $query->whereDate('orders.created_at', '<=', $filters['end_date'])),
                AllowedFilter::scope('type', 'fromType'),
                AllowedFilter::callback('product', function ($query, $value) {
                    $query->whereHas('items.product', fn ($query) => $query->where('name', 'LIKE', "%$value%"));
                }),
            ])
            ->sum('net_amount') ?? 0;
    }

    public function getTotalRevenuePaidSubscriptions(): float
    {
        $filters = $this->handleFilters();

        return QueryBuilder::for(Order::class)
            ->fromShop()
            ->isSubscription()
            ->filterByPaymentStatus('paid')
            ->allowedFilters([
                AllowedFilter::callback('start_date', fn ($query, $value) => $query->whereDate('orders.created_at', '>=', $filters['start_date'])),
                AllowedFilter::callback('end_date', fn ($query, $value) => $query->whereDate('orders.created_at', '<=', $filters['end_date'])),
                AllowedFilter::scope('type', 'fromType'),
                AllowedFilter::callback('product', function ($query, $value) {
                    $query->whereHas('items.product', fn ($query) => $query->where('name', 'LIKE', "%$value%"));
                }),
            ])
            ->sum('net_amount') ?? 0;
    }

    public function getInfosOrdersByPaymentMethod(): Collection
    {
        $filters = $this->handleFilters();

        return QueryBuilder::for(Order::class)
            ->selectRaw('
                order_payments.payment_method,
                COUNT(DISTINCT orders.id) as total_orders,
                SUM(orders.net_amount) as total_amount,
                ROUND(
                    COUNT(DISTINCT orders.id) * 100.0 / SUM(COUNT(DISTINCT orders.id)) OVER(), 2
                ) as percentage
            ')
            ->join('order_payments', 'orders.id', '=', 'order_payments.order_id')
            ->fromShop()
            ->filterByPaymentStatus('paid')
            ->allowedFilters([
                AllowedFilter::callback('start_date', fn ($query, $value) => $query->whereDate('orders.created_at', '>=', $filters['start_date'])),
                AllowedFilter::callback('end_date', fn ($query, $value) => $query->whereDate('orders.created_at', '<=', $filters['end_date'])),
                AllowedFilter::scope('type', 'fromType'),
                AllowedFilter::callback('product', function ($query, $value) {
                    $query->whereHas('items.product', fn ($query) => $query->where('name', 'LIKE', "%$value%"));
                }),
            ])
            ->groupBy('order_payments.payment_method')
            ->toBase()
            ->get()
            ->keyBy('payment_method');
    }

    public function getTotalCommissionAffiliatePaidOrders()
    {
        $filters = $this->handleFilters();

        $typePerson   = 'AFFILIATE';
        $usernameShop = user()->shop()?->username_banking;

        if (!$usernameShop) {
            return null;
        }

        $ordersUserAffiliate = QueryBuilder::for(Order::class)
            ->filterByPaymentStatus('paid')
            ->whereNotNull('attributes->splitGateway')
            ->whereJsonContains('attributes->splitGateway', [
                'username'        => $usernameShop,
                'splitTypePerson' => $typePerson,
            ])
            ->allowedFilters([
                AllowedFilter::callback('start_date', fn ($query, $value) => $query->whereDate('orders.created_at', '>=', $filters['start_date'])),
                AllowedFilter::callback('end_date', fn ($query, $value) => $query->whereDate('orders.created_at', '<=', $filters['end_date'])),
            ])
            ->get();

        return $this->sumCommissions($ordersUserAffiliate, $typePerson, $usernameShop);
    }

    public function getTotalCommissionCoproducerPaidOrders()
    {
        $filters = $this->handleFilters();

        $typePerson   = 'CO_PRODUCER';
        $usernameShop = user()->shop()?->username_banking;

        if (!$usernameShop) {
            return null;
        }

        $ordersUserCoproducer = QueryBuilder::for(Order::class)
            ->filterByPaymentStatus('paid')
            ->whereNotNull('attributes->splitGateway')
            ->whereJsonContains('attributes->splitGateway', [
                'username'        => $usernameShop,
                'splitTypePerson' => $typePerson,
            ])
            ->allowedFilters([
                AllowedFilter::callback('start_date', fn ($query, $value) => $query->whereDate('orders.created_at', '>=', $filters['start_date'])),
                AllowedFilter::callback('end_date', fn ($query, $value) => $query->whereDate('orders.created_at', '<=', $filters['end_date'])),
            ])
            ->get();

        return $this->sumCommissions($ordersUserCoproducer, $typePerson, $usernameShop);
    }

    public function getTopSellingProducts(): Collection
    {
        $filters = $this->handleFilters();

        return QueryBuilder::for(Order::class)
            ->selectRaw('
                COUNT(orders.id) AS quantity_orders, 
                SUM(orders.net_amount) AS total_amount,
                products.name AS product_name,
                parent_products.name AS parent_product_name
            ')
            ->filterByPaymentStatus('paid')
            ->fromShop()
            ->allowedFilters([
                AllowedFilter::callback('start_date', fn ($query, $value) => $query->whereDate('orders.created_at', '>=', $filters['start_date'])),
                AllowedFilter::callback('end_date', fn ($query, $value) => $query->whereDate('orders.created_at', '<=', $filters['end_date'])),
                AllowedFilter::scope('type', 'fromType'),
            ])
            ->join('item_orders', 'orders.id', '=', 'item_orders.order_id')
            ->join('products', 'item_orders.product_id', '=', 'products.id')
            ->leftJoin('products as parent_products', 'products.parent_id', '=', 'parent_products.id')
            ->groupBy('products.name')
            ->orderByDesc('quantity_orders')
            ->limit(5)
            ->get();
    }

    public function getPercentOrdersRefunded(): ?object
    {
        $filters = $this->handleFilters();

        return QueryBuilder::for(Order::class)
            ->join('order_payments', 'orders.id', '=', 'order_payments.order_id')
            ->selectRaw('
                COUNT(DISTINCT orders.id) as total_orders,
                COUNT(DISTINCT CASE WHEN order_payments.payment_status IN (' . implode(',', array_map(fn ($s) => \DB::getPdo()->quote($s), Order::$statusForRefunded)) . ') THEN orders.id ELSE NULL END) as refunded_orders,
                (COUNT(DISTINCT CASE WHEN order_payments.payment_status IN (' . implode(',', array_map(fn ($s) => \DB::getPdo()->quote($s), Order::$statusForRefunded)) . ') THEN orders.id ELSE NULL END) / COUNT(DISTINCT orders.id)) * 100 as percentage_refunded
            ')
            ->fromShop()
            ->allowedFilters([
                AllowedFilter::callback('start_date', fn ($query, $value) => $query->whereDate('orders.created_at', '>=', $filters['start_date'])),
                AllowedFilter::callback('end_date', fn ($query, $value) => $query->whereDate('orders.created_at', '<=', $filters['end_date'])),
                AllowedFilter::scope('type', 'fromType'),
                AllowedFilter::callback('product', function ($query, $value) {
                    $query->whereHas('items.product', fn ($query) => $query->where('name', 'LIKE', "%$value%"));
                }),
            ])
            ->first();
    }

    public function getPercentOrdersChargeback(): ?object
    {
        $filters = $this->handleFilters();

        return QueryBuilder::for(Order::class)
            ->join('order_payments', 'orders.id', '=', 'order_payments.order_id')
            ->selectRaw('
                COUNT(DISTINCT orders.id) as total_orders,
                COUNT(DISTINCT CASE WHEN order_payments.payment_status IN (' . implode(',', array_map(fn ($s) => \DB::getPdo()->quote($s), Order::$statusForChargeback)) . ') THEN orders.id ELSE NULL END) as refunded_orders,
                (COUNT(DISTINCT CASE WHEN order_payments.payment_status IN (' . implode(',', array_map(fn ($s) => \DB::getPdo()->quote($s), Order::$statusForChargeback)) . ') THEN orders.id ELSE NULL END) / COUNT(DISTINCT orders.id)) * 100 as percentage_chargeback
            ')
            ->fromShop()
            ->allowedFilters([
                AllowedFilter::callback('start_date', fn ($query, $value) => $query->whereDate('orders.created_at', '>=', $filters['start_date'])),
                AllowedFilter::callback('end_date', fn ($query, $value) => $query->whereDate('orders.created_at', '<=', $filters['end_date'])),
                AllowedFilter::scope('type', 'fromType'),
                AllowedFilter::callback('product', function ($query, $value) {
                    $query->whereHas('items.product', fn ($query) => $query->where('name', 'LIKE', "%$value%"));
                }),
            ])
            ->first();
    }

    public function getQuantitiesSubscriptionsPerSituation(): ?object
    {
        $filters = $this->handleFilters();

        return QueryBuilder::for(Order::class)
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
            ->fromShop()
            ->isSubscription()
            ->allowedFilters([
                AllowedFilter::callback('start_date', fn ($query, $value) => $query->whereDate('orders.created_at', '>=', $filters['start_date'])),
                AllowedFilter::callback('end_date', fn ($query, $value) => $query->whereDate('orders.created_at', '<=', $filters['end_date'])),
                AllowedFilter::scope('type', 'fromType'),
                AllowedFilter::callback('product', function ($query, $value) {
                    $query->whereHas('items.product', fn ($query) => $query->where('name', 'LIKE', "%$value%"));
                }),
            ])
            ->first();
    }

    public function geTopAffiliates(): Collection
    {
        $filters = $this->handleFilters();

        return QueryBuilder::for(Order::class)
            ->selectRaw('
                COUNT(orders.id) AS quantity_orders, 
                SUM(orders.net_amount) AS total_amount,
                users.name AS affiliate_name
            ')
            ->filterByPaymentStatus('paid')
            ->fromShop()
            ->allowedFilters([
                AllowedFilter::callback('start_date', fn ($query, $value) => $query->whereDate('orders.created_at', '>=', $filters['start_date'])),
                AllowedFilter::callback('end_date', fn ($query, $value) => $query->whereDate('orders.created_at', '<=', $filters['end_date'])),
                AllowedFilter::scope('type', 'fromType'),
            ])
            ->join('affiliates', 'orders.affiliate_id', '=', 'affiliates.id')
            ->join('users', 'affiliates.user_id', '=', 'users.id')
            ->where('users.email', '!=', user()->email)
            ->groupBy('users.name')
            ->orderByDesc('quantity_orders')
            ->limit(5)
            ->get();
    }

    public function ordersGroupedByDay(): Collection
    {
        $filters = $this->handleFilters();

        $dataOrdersPerDay = QueryBuilder::for(Order::class)
            ->selectRaw("
            DATE(orders.created_at) AS date, 
            DATE_FORMAT(orders.created_at, '%d/%m/%Y') AS day, 
            COUNT(orders.id) AS quantity_orders, 
            SUM(orders.net_amount) AS total_net_amount
        ")
            ->fromShop()
            ->filterByPaymentStatus('paid')
            ->allowedFilters([
                AllowedFilter::callback('start_date', fn ($query, $value) => $query->whereDate('orders.created_at', '>=', $filters['start_date'])),
                AllowedFilter::callback('end_date', fn ($query, $value) => $query->whereDate('orders.created_at', '<=', $filters['end_date'])),
                AllowedFilter::scope('type', 'fromType'),
                AllowedFilter::callback('product', function ($query, $value) {
                    $query->whereHas('items.product', fn ($query) => $query->where('name', 'LIKE', "%$value%"));
                }),
            ])
            ->groupBy('date')
            ->toBase()
            ->get()
            ->keyBy('date');

        $allDates    = collect();
        $currentDate = Carbon::parse($filters['start_date'])->copy();

        while ($currentDate->lte(Carbon::parse($filters['end_date']))) {
            $allDates->push($currentDate->toDateString());
            $currentDate->addDay();
        }

        return $allDates->map(fn ($date) => $dataOrdersPerDay->get($date, [
            'date'             => $date,
            'day'              => Carbon::parse($date)->format('d/m/Y'),
            'quantity_orders'  => 0,
            'total_net_amount' => 0,
        ]));
    }

    public function getTotalAbandonedCarts(): int
    {
        $filters = $this->handleFilters();

        return QueryBuilder::for(AbandonedCart::class)
            ->whereHas('product', fn ($query) => $query->where('shop_id', user()?->shop()?->id))
            ->where('status', SituationProductEnum::PENDING)
            ->allowedFilters([
                AllowedFilter::callback('start_date', fn ($query, $value) => $query->whereDate('abandoned_carts.created_at', '>=', $filters['start_date'])),
                AllowedFilter::callback('end_date', fn ($query, $value) => $query->whereDate('abandoned_carts.created_at', '<=', $filters['end_date'])),
            ])
            ->count();
    }

    public function handleFilters(): array
    {
        $period                  = request()->input('filter.period', now()->subDays(7)->format('d/m/Y') . ' - ' . now()->format('d/m/Y'));
        [$start_date, $end_date] = explode(' - ', $period);
        $start_date              = Carbon::createFromFormat('d/m/Y', $start_date)->format('Y-m-d');
        $end_date                = Carbon::createFromFormat('d/m/Y', $end_date)->format('Y-m-d');

        request()->merge([
            'filter' => [
                'period'     => $period,
                'start_date' => $start_date,
                'end_date'   => $end_date,
            ],
        ]);

        return compact('start_date', 'end_date');
    }

    private function sumCommissions(Collection $orders, string $typePerson, string $usernameShop)
    {
        return $orders->sum(function ($order) use ($typePerson, $usernameShop) {
            return collect($order->getValueSchemalessAttributes('splitGateway'))
                ->where('username', $usernameShop)
                ->where('splitTypePerson', $typePerson)
                ->sum('valueSplit');
        });
    }
}
