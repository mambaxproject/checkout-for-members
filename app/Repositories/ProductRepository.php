<?php

namespace App\Repositories;

use App\Models\CategoryProduct;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProductRepository
{
    public function __construct(
        protected Product $model
    ) {}

    private function buildQuery(): Builder
    {
        $query = $this->model::query()
            ->with(['media']);

        $search = request('search');
        if (!empty($search)) {
            $query = $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }


        $category_id = request('category_id');
        if (!empty($category_id)) {
            $query = $query->whereHas('categories', function ($query) use ($category_id) {
                $query->where('id', $category_id);
            });
        }

        $period = request('period');
        if (!empty($period)) {
            $query = match ($period) {
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

    public function all(): Collection
    {
        return $this->buildQuery()
            ->active()
            ->latest()
            ->get();
    }

    public function allToHome(): Collection
    {
        return CategoryProduct::with(['products.media', 'products' => function ($query) {
            $query->latest()->limit(10);
        }])->get();
    }

    public function paginate(int $limit = 15): LengthAwarePaginator
    {
        return $this->buildQuery()
            ->paginate($limit);
    }

    public function totalEvents(): int
    {
        return $this->buildQuery()
            ->active()
            ->count();
    }

    public function getAvailableCreateActionByUserId(int $userId): Collection
    {
        $subquery = DB::table('notification_actions')
            ->select('product_id')
            ->where('user_id', $userId);


        return $this->model::join('shops', 'shop_id', '=', 'shops.id')
            ->where('owner_id', $userId)
            ->whereNotIn('products.id', $subquery)
            ->WhereNull('parent_id')
            ->where('products.situation', '=', 'PUBLISHED')
            ->where('products.status', '=', 'ACTIVE')
            ->select(
                'products.id',
                'products.name'
            )
            ->get();
    }

    public function getById(int $productId): Product
    {
        return $this->model::where('id', $productId)->first();
    }

    public function getByIdAndUserId(int $productId, int $userId): Collection
    {
        return $this->model::where('products.id', $productId)
            ->join('shops', 'shop_id', '=', 'shops.id')
            ->where('owner_id', $userId)
            ->get();
    }
}
