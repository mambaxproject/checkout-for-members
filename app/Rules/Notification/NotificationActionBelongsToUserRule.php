<?php

namespace App\Rules\Notification;

use App\Models\NotificationAction;
use App\Repositories\NotificationActionRepository;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class NotificationActionBelongsToUserRule implements ValidationRule
{
    private NotificationActionRepository $notificationActionRepository;

    public function __construct()
    {
        $this->notificationActionRepository = new NotificationActionRepository(new NotificationAction());
    }

    public function validate(string $attribute, mixed $actionId, Closure $fail): void
    {
        if (!is_numeric($actionId)) {
            return;
        }
        $user = Auth::user();
        $action = $this->notificationActionRepository->getByIdAndUserId($actionId, $user->id)->first();
        if (is_null($action)) {
            $fail('Ação não encontrada');
        }
    }
}
