<?php

namespace App\Rules\Notification;

use App\Models\NotificationAction;
use App\Repositories\NotificationActionRepository;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class NotificationActionNameUpdateRule implements ValidationRule
{
    private NotificationActionRepository $notificationActionRepository;
    private int $actionId;

    public function __construct(int $actionId)
    {
        $this->notificationActionRepository = new NotificationActionRepository(new NotificationAction());
        $this->actionId = $actionId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = Auth::user();
        $notificationAction = $this->notificationActionRepository->getByNameAndUserId($value, $user->id)->first();

        if (is_null($notificationAction)) {
            return;
        }

        if ($notificationAction->id == $this->actionId) {
            return;
        }

        $fail('Já existe outra ação com esse nome');
    }
}
