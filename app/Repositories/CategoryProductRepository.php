<?php

namespace App\Repositories;

use App\Models\CategoryProduct;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryProductRepository
{
    public function __construct(
        protected CategoryProduct $model
    ) { }

    private function buildQuery(): Builder
    {
        $query = $this->model::query();

        $search = request('search');
        if (!empty($search)) {
            $query = $query->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        return $query;
    }

    public function all(): Collection
    {
        return cache()->remember('categories_product', 60 * 60 * 24, function () {
            return $this->buildQuery()
                ->oldest('name')
                ->get();
        });
    }

    public function paginate(int $limit = 15): LengthAwarePaginator
    {
        return $this->buildQuery()
                ->latest()
                ->paginate($limit);
    }

}
