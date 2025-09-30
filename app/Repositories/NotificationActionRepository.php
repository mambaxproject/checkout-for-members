<?php

namespace App\Repositories;

use App\Models\NotificationAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class NotificationActionRepository
{

    public function __construct(
        protected NotificationAction $model
    ) {}

    public function getByProductId(int $productId): Collection
    {
        return $this->model::where('product_id', $productId)
            ->get();
    }

    public function create(array $body): NotificationAction
    {
        try {
            return $this->model::create($body);
        } catch (\Throwable $th) {
            Log::channel('notification')->error(
                'Erro ao criar Action.',
                [
                    'error' => $th->getMessage(),
                    'function' => 'NotificationActionRepository.create',
                    'body' => $body
                ]
            );
            throw $th;
        }
    }

    public function getByUserId(int $userId, array $dataParams = []): LengthAwarePaginator
    {
        $queryBuilder = $this->model::where('user_id', $userId)
            ->join('products', 'products.id', '=', 'notification_actions.product_id')
            ->select(
                'notification_actions.name AS actionName',
                'notification_actions.updated_at',
                'products.name AS nameProduct',
                'notification_actions.status AS actionStatus',
                'notification_actions.id AS id',
                'products.deleted_at AS productRemoved'
            );
        $this->checkHasFilters($queryBuilder, $dataParams);
        return $queryBuilder->orderBy('notification_actions.updated_at', 'DESC')->paginate(10);
    }

    private function checkHasFilters(Builder $queryBuilder, array $dataParams = []): void
    {
        if (array_key_exists('name_action', $dataParams)) {
            $queryBuilder->where('notification_actions.name', 'like', '%' . $dataParams['name_action'] . '%');
        }

        if (array_key_exists('status_action', $dataParams)) {
            $queryBuilder->where('notification_actions.status', (bool) $dataParams['status_action'])
                ->whereNull('products.deleted_at');
        }

        if (array_key_exists('name_product', $dataParams)) {
            $queryBuilder->where('products.name', 'like', '%' . $dataParams['name_product'] . '%');
        }

        if (array_key_exists('product_deleted_at', $dataParams)) {
            $queryBuilder->whereNotNull('products.deleted_at');
        }
    }

    public function findByIdWithProduct(int $actionId): NotificationAction
    {
        return $this->model::where('notification_actions.id', $actionId)
            ->join('products', 'products.id', '=', 'notification_actions.product_id')
            ->select(
                'notification_actions.name AS actionName',
                'products.name AS nameProduct',
                'notification_actions.id',
                'notification_actions.description AS descAction'
            )->firstOrFail();
    }

    public function getByNameAndUserId(string $name, int $userId): Collection
    {
        return $this->model::where('name', $name)
            ->where('user_id', $userId)
            ->get();
    }

    public function update(array $body, int $actionId): void
    {
        try {
            $this->model::where('id', $actionId)->update($body);
        } catch (\Throwable $th) {
            Log::channel('notification')->error(
                'Erro ao atualizar Action.',
                [
                    'error' => $th->getMessage(),
                    'function' => 'NotificationActionRepository.update',
                    'body' => $body
                ]
            );
            throw $th;
        }
    }

    public function findById(int $actionId): NotificationAction
    {
        return $this->model::where('notification_actions.id', $actionId)->firstOrFail();
    }

    public function getByIdAndUserId(int $actionId, int $userId): Collection
    {
        return $this->model::where('notification_actions.id', $actionId)
            ->where('user_id', $userId)
            ->get();
    }
}
