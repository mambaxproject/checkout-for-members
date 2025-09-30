<?php

namespace App\Repositories;

use App\Models\City;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CityRepository
{
    public function __construct(
        protected City $model
    ) { }

    private function buildQuery(): Builder
    {
        $query = $this->model::query()
            ->with('state');

        $q = request('q');
        if (!empty($q)) {
            $query = $query->where('name', 'like', "%{$q}%");
        }

        return $query;
    }

    public function all(): Collection
    {
        return cache()->remember('cities', 60 * 60 * 24, function () {
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
