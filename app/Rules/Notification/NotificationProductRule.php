<?php

namespace App\Rules\Notification;

use App\Models\NotificationAction;
use App\Repositories\NotificationActionRepository;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NotificationProductRule implements ValidationRule
{
    private NotificationActionRepository $notificationActionRepository;

    public function __construct()
    {
        $this->notificationActionRepository = new NotificationActionRepository(new NotificationAction());
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $notificationAction = $this->notificationActionRepository->getByProductId($value)->first();
        if (!is_null($notificationAction)) {
            $fail('Esse produto já possue cadastro');
        }
    }
}
