<?php

namespace App\Rules\Notification;

use Illuminate\Contracts\Validation\Rule;

class UniqueEventNotificationIdsRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        $eventIds = array_column($value, 'eventId');
        return count($eventIds) === count(array_unique($eventIds));
    }

    public function message()
    {
        return 'O campo eventId dentro de Notifications deve ser único.';
    }
}
