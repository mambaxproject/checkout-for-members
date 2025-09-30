<?php

namespace App\Http\Requests\Notification;

use App\Rules\Notification\NotificationActionBelongsToUserRule;
use Illuminate\Foundation\Http\FormRequest;

class EditNotificationActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'actionId' => $this->route('actionId')
        ]);
    }

    public function rules()
    {
        return [
            'actionId' => ['required', 'numeric', new NotificationActionBelongsToUserRule()]
        ];
    }

    public function toArray(): array
    {
        return [
            'actionId' => $this->actionId
        ];
    }
}
