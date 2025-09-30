<?php

namespace App\Repositories;

use App\Models\CustomNotification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class CustomNotificationRepository
{
    public function __construct(
        protected CustomNotification $model
    ) {}

    public function insert(array $body): void
    {
        try {
            $this->model::insert($body);
        } catch (\Throwable $th) {
            Log::channel('notification')->error(
                'Erro ao inserir Notification.',
                [
                    'error' => $th->getMessage(),
                    'function' => 'NotificationRepository.insert',
                    'body' => $body
                ]
            );
            throw $th;
        }
    }

    public function getByActionId(int $actionId): Collection
    {
        return $this->model::where('action_id', $actionId)->get();
    }

    public function update(array $body, int $notificationId): void
    {
        try {
            $this->model::where('id', $notificationId)->update($body);
        } catch (\Throwable $th) {
            Log::channel('notification')->error(
                'Erro ao atualizar notification.',
                [
                    'error' => $th->getMessage(),
                    'function' => 'NotificationRepository.update',
                    'body' => $body
                ]
            );
            throw $th;
        }
    }

    public function getByEventIdAndParentId(int $eventId, int $parentId): Collection
    {
        return $this->model::join('notification_actions', 'notification_actions.id', '=', 'custom_notifications.action_id')
            ->where('event_id', $eventId)
            ->where('product_id', $parentId)
            ->where('notification_actions.status', true)
            ->where('custom_notifications.status', true)
            ->get();
    }
}
