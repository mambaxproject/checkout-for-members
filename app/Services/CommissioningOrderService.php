<?php

namespace App\Services;

use App\Models\{CommissioningOrder};
use Illuminate\Pagination\Paginator;
use Illuminate\Support\{Carbon, Collection};
use Spatie\QueryBuilder\{AllowedFilter, QueryBuilder};

class CommissioningOrderService
{
    public function getTotalValueCommissioningFromPaidOrdersShop(): float
    {
        $filters = $this->handleFilters();

        return QueryBuilder::for(CommissioningOrder::class)
            ->whereHas('order', function ($query) {
                $query->fromShop()
                    ->filterByPaymentStatus('paid');
            })
            ->allowedFilters([
                AllowedFilter::callback('start_date', fn ($query, $value) => $query->whereDate('commissioning_orders.created_at', '>=', $filters['start_date'])),
                AllowedFilter::callback('end_date', fn ($query, $value) => $query->whereDate('commissioning_orders.created_at', '<=', $filters['end_date'])),
            ])
            ->sum('value') ?? 0;
    }

    public function getQuantityCommissionedShop(): int
    {
        $quantityAffiliatesShop = user()->shop()->affiliates()->active()->distinct('user_id')->count();

        $quantityCoproducersShop = user()->shop()->coproducers()->active()->distinct('user_id')->count();

        return $quantityAffiliatesShop + $quantityCoproducersShop;
    }

    public function getQuantityOrdersCommissionedShop(): int
    {
        $filters = $this->handleFilters();

        return QueryBuilder::for(CommissioningOrder::class)
            ->distinct('order_id')
            ->whereHas('order', fn ($query) => $query->fromShop())
            ->allowedFilters([
                AllowedFilter::callback('start_date', fn ($query, $value) => $query->whereDate('commissioning_orders.created_at', '>=', $filters['start_date'])),
                AllowedFilter::callback('end_date', fn ($query, $value) => $query->whereDate('commissioning_orders.created_at', '<=', $filters['end_date'])),
            ])
            ->count('order_id');
    }

    public function getTopCommissionedShop(): Collection
    {
        $filters = $this->handleFilters();

        return QueryBuilder::for(CommissioningOrder::class)
            ->selectRaw('
                COUNT(commissioning_orders.id) AS quantity_orders, 
                SUM(commissioning_orders.value) AS total_amount,
                users.name AS commissioned_name,
                commissioning_orders.type
            ')
            ->whereHas('order', function ($query) {
                $query->fromShop()
                    ->filterByPaymentStatus('paid');
            })
            ->allowedFilters([
                AllowedFilter::callback('start_date', fn ($query, $value) => $query->whereDate('commissioning_orders.created_at', '>=', $filters['start_date'])),
                AllowedFilter::callback('end_date', fn ($query, $value) => $query->whereDate('commissioning_orders.created_at', '<=', $filters['end_date'])),
            ])
            ->join('users', 'commissioning_orders.commissioned_id', '=', 'users.id')
            ->groupBy('users.name')
            ->orderByDesc('total_amount')
            ->limit(10)
            ->toBase()
            ->get();
    }

    public function getCommissionedOrdersShop(): Paginator
    {
        $filters = $this->handleFilters();

        return QueryBuilder::for(CommissioningOrder::class)
            ->withWhereHas('order', fn ($query) => $query->fromShop())
            ->with([
                'commissioned:id,name',
                'order.payments',
                'order.items' => fn ($query) => $query->with(['product.parentProduct:id,name']),
            ])
            ->orderByDesc('order_id')
            ->allowedFilters([
                AllowedFilter::callback('start_date', fn ($query, $value) => $query->whereDate('commissioning_orders.created_at', '>=', $filters['start_date'])),
                AllowedFilter::callback('end_date', fn ($query, $value) => $query->whereDate('commissioning_orders.created_at', '<=', $filters['end_date'])),
            ])
            ->simplePaginate()
            ->withQueryString();
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

}
