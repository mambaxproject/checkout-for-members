<?php

namespace App\Repositories;

use App\Models\State;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class StateRepository
{
    public function __construct(
        protected State $model
    ) { }

    private function buildQuery(): Builder
    {
        $query = $this->model::query();

        $q = request('q');
        if (!empty($q)) {
            $query = $query->where('name', 'like', "%{$q}%")
                ->orWhere('uf', 'like', "%{$q}%")
                ->orWhere('code', 'like', "%{$q}%");
        }

        return $query;
    }

    public function all(): Collection
    {
        return cache()->remember('states', 60 * 60 * 24, function () {
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
