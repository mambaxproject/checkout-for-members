<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderRepository
{
    public function __construct(
        protected Order $model
    ) {}

    private function buildQuery(): Builder
    {
        return $this->model::query()
            ->with(['items', 'items.product', 'user']);
    }

    public function create(array $data): Order
    {
        $order = $this->model::query()->create($data);;

        $order->items()->createMany($data['items']);

        $order->payments()->createMany($data['payments']);

        if (isset($data['discounts']) && !is_null($data['discounts'][0]['discount_coupon_id'])) {
            $order->discounts()->createMany($data['discounts']);
        }

        return $order;
    }

    public function all(): Collection
    {
        return $this->buildQuery()
            ->latest()
            ->get();
    }

    public function paginate(int $limit = 15): LengthAwarePaginator
    {
        return $this->buildQuery()
            ->latest()
            ->paginate($limit);
    }

    public function getOrdersByUser(): Collection
    {
        return $this->buildQuery()
            ->where('user_id', auth()->user()->id)
            ->latest()
            ->get();
    }

    public function getProductSoldCount(int $organization_id, $groupBy = 'day'): Collection
    {
        $query = $this->model::query()
            ->selectRaw(
                "(DATE_FORMAT(created_at, '%d/%m/%Y')) as formattedDate,
                COUNT(id) as quantity,
                SUM(items.quantity) as total_sold"
            )
            ->whereHas('items', function ($query) use ($organization_id) {
                $query->whereHas('product', function ($query) use ($organization_id) {
                    $query->where('organization_id', $organization_id);
                });
            })
            ->whereHas('payments', fn($query) => $query->whereIn('status', Order::$statusForPaid));

        if ($groupBy === 'day') {
            $query->groupByRaw("DATE_FORMAT(created_at, '%d/%m/%Y')");
        }

        if ($groupBy === 'hour') {
            $query->groupBy("DATE_FORMAT(created_at, '%H')");
        }

        $this->applyFilters($query, request()->all());

        return $query->get();
    }

    private function applyFilters(Builder $query, array $filters): Builder
    {
        if (!empty($filters['period'])) {
            $query = match ($filters['period']) {
                'today' => $query->whereDate('start_at', today()),
                'yesterday' => $query->whereDate('start_at', today()->subDay()),
                'this_week' => $query->whereBetween('start_at', [today()->startOfWeek(), today()->endOfWeek()]),
                'this_month' => $query->whereBetween('start_at', [today()->startOfMonth(), today()->endOfMonth()]),
                'this_weekend' => $query->whereBetween('start_at', [today()->startOfWeekend(), today()->endOfWeekend()]),
                'next_week' => $query->whereBetween('start_at', [today()->addWeek(), today()->addWeek()->endOfWeek()]),
                'next_month' => $query->whereBetween('start_at', [today()->addMonth(), today()->addMonth()->endOfMonth()]),
                'next_weekend' => $query->whereBetween('start_at', [today()->addWeekend(), today()->addWeekend()->endOfWeekend()]),
            };
        }

        return $query;
    }

    public function getOrdersGeneralNotificable(int $orderId): Order
    {
        return $this->model::join('customers', 'customers.id', '=', 'orders.user_id')
            ->join('item_orders', 'item_orders.order_id', '=', 'orders.id')
            ->join('products', 'item_orders.product_id', '=', 'products.id')
            ->where('orders.id', $orderId)
            ->select(
                'customers.name AS nameClient',
                'customers.email AS emailClient',
                'products.code AS linkProduct',
                'products.parent_id as parentId',
                'customers.phone_number as phoneNumber'
            )
            ->first();
    }
}
