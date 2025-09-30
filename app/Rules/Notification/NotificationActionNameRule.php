<?php

namespace App\Rules\Notification;

use App\Models\NotificationAction;
use App\Repositories\NotificationActionRepository;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class NotificationActionNameRule implements ValidationRule
{
    private NotificationActionRepository $notificationActionRepository;

    public function __construct()
    {
        $this->notificationActionRepository = new NotificationActionRepository(new NotificationAction());
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = Auth::user();
        $notificationAction = $this->notificationActionRepository->getByNameAndUserId($value, $user->id)->first();
        if (!is_null($notificationAction)) {
            $fail('Já existe uma ação com esse nome');
        }
    }
}
