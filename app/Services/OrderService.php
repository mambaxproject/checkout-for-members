<?php

namespace App\Services;

use App\Models\{Order};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class OrderService
{
    public function __construct(
        public Order $model
    ) {}

    public function getAllOrdersWithPaymentExpired(): Collection
    {
        return $this->model->isPaymentExpired()
            ->withWhereHas('user:id,name,email,phone_number')
            ->withWhereHas('shop')
            ->with(['items.product'])
            ->whereNull('attributes->notifiedOrderFailed')
            ->latest('id')
            ->get(['id', 'user_id', 'amount', 'created_at']);
    }

    public function getAverageDailyTurnoverFromOrders(): float
    {
        return $this->model::query()
            ->selectRaw('
                COALESCE(SUM(net_amount) / NULLIF(COUNT(DISTINCT DATE(created_at)), 0), 0) AS average_daily_turnover
            ')
            ->isOrder()
            ->when(request()->isNotFilled('filter.payment_status'), fn ($query) => $query->filterByPaymentStatus('paid'))
            ->when(request()->filled('filter.payment_status'), fn ($query) => $query->filterByPaymentStatus(request('filter.payment_status')))
            ->when(request()->isNotFilled('filter.type'), fn ($query) => $query->allForUser())
            ->when(request()->filled('filter.type'), fn ($query) => $query->fromType(request('filter.type')))
            ->when(request()->filled('filter.payment_method'), fn ($query) => $query->filterByPaymentMethod(request('filter.payment_method')))
            ->when(request()->isNotFilled('filter.start_at') && request()->isNotFilled('filter.end_at'), function (Builder $builder) {
                $builder->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            })
            ->when(request()->filled('filter.start_at'), fn ($query) => $query->whereDate('created_at', '>=', request('filter.start_at')))
            ->when(request()->filled('filter.end_at'), fn ($query) => $query->whereDate('created_at', '<=', request('filter.end_at')))
            ->value('average_daily_turnover');
    }

    public function getAverageDailyTurnoverLastWeekFromOrders(): float
    {
        return $this->model::query()
            ->selectRaw('
                COALESCE(SUM(net_amount) / NULLIF(COUNT(DISTINCT DATE(created_at)), 0), 0) AS average_daily_turnover
            ')
            ->isOrder()
            ->when(request()->isNotFilled('filter.payment_status'), fn ($query) => $query->filterByPaymentStatus('paid'))
            ->when(request()->filled('filter.payment_status'), fn ($query) => $query->filterByPaymentStatus(request('filter.payment_status')))
            ->when(request()->isNotFilled('filter.type'), fn ($query) => $query->allForUser())
            ->when(request()->filled('filter.type'), fn ($query) => $query->fromType(request('filter.type')))
            ->when(request()->filled('filter.payment_method'), fn ($query) => $query->filterByPaymentMethod(request('filter.payment_method')))
            ->when(request()->isNotFilled('filter.start_at') && request()->isNotFilled('filter.end_at'), function (Builder $builder) {
                $builder->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()]);
            })
            ->when(request()->filled('filter.start_at'), fn ($query) => $query->whereDate('created_at', '>=', request('filter.start_at')))
            ->when(request()->filled('filter.end_at'), fn ($query) => $query->whereDate('created_at', '<=', request('filter.end_at')))
            ->value('average_daily_turnover');
    }

    public function getTotalRevenueFromOrders(): float
    {
        return $this->model::query()
            ->selectRaw('
                COALESCE(SUM(net_amount), 0) AS total_revenue
            ')
            ->isOrder()
            ->when(request()->isNotFilled('filter.payment_status'), fn ($query) => $query->filterByPaymentStatus('paid'))
            ->when(request()->filled('filter.payment_status'), fn ($query) => $query->filterByPaymentStatus(request('filter.payment_status')))
            ->when(request()->isNotFilled('filter.type'), fn ($query) => $query->allForUser())
            ->when(request()->filled('filter.type'), fn ($query) => $query->fromType(request('filter.type')))
            ->when(request()->filled('filter.payment_method'), fn ($query) => $query->filterByPaymentMethod(request('filter.payment_method')))
            ->when(request()->filled('filter.start_at'), fn ($query) => $query->whereDate('created_at', '>=', request('filter.start_at')))
            ->when(request()->filled('filter.end_at'), fn ($query) => $query->whereDate('created_at', '<=', request('filter.end_at')))
            ->value('total_revenue');
    }

    public function getTotalRevenueLastWeekFromOrders(): float
    {
        return $this->model::query()
            ->selectRaw('
                COALESCE(SUM(net_amount), 0) AS total_revenue
            ')
            ->isOrder()
            ->when(request()->isNotFilled('filter.payment_status'), fn ($query) => $query->filterByPaymentStatus('paid'))
            ->when(request()->filled('filter.payment_status'), fn ($query) => $query->filterByPaymentStatus(request('filter.payment_status')))
            ->when(request()->isNotFilled('filter.type'), fn ($query) => $query->allForUser())
            ->when(request()->filled('filter.type'), fn ($query) => $query->fromType(request('filter.type')))
            ->when(request()->filled('filter.payment_method'), fn ($query) => $query->filterByPaymentMethod(request('filter.payment_method')))
            ->when(request()->isNotFilled('filter.start_at') && request()->isNotFilled('filter.end_at'), function (Builder $builder) {
                $builder->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()]);
            })
            ->when(request()->filled('filter.start_at'), fn ($query) => $query->whereDate('created_at', '>=', request('filter.start_at')))
            ->when(request()->filled('filter.end_at'), fn ($query) => $query->whereDate('created_at', '<=', request('filter.end_at')))
            ->value('total_revenue');
    }

}
