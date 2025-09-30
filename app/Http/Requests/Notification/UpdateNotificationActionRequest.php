<?php

namespace App\Http\Requests\Notification;

use App\Rules\Notification\NotificationActionBelongsToUserRule;
use App\Rules\Notification\NotificationActionNameUpdateRule;
use App\Rules\Notification\UniqueEventNotificationIdsRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateNotificationActionRequest extends FormRequest
{

    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'actionId' => ['required', 'integer', new NotificationActionBelongsToUserRule()],
            'nameAction' => ['required', 'string', 'max:255', new NotificationActionNameUpdateRule($this->actionId)],
            'descAction' => ['string', 'nullable'],
            'Notifications' => ['required', 'array', new UniqueEventNotificationIdsRule()],
            'Notifications.*.text' => ['string', 'nullable'],
            'Notifications.*.dispatchTime' => ['required', 'integer', 'min:0'],
            'Notifications.*.image' => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:2048', 'nullable'],
        ];
    }

    public function toArray(): array
    {
        return [
            'action' => [
                $this->actionId => [
                    'name' => $this->nameAction,
                    'description' => $this->descAction,
                ]
            ],
            'notifications' => $this->getNotifications(),
        ];
    }

    private function getNotifications(): array
    {
        return array_map(function ($notification) {
            return [
                $notification['id'] => array_filter([
                    'text_whatsapp' => $notification['text'],
                    'dispatch_time' => $notification['dispatchTime'],
                    'url_embed' => isset($notification['image']) ? $notification['image'] : $notification['oldImage'],
                    'status' => isset($notification['status']) ? true : false,
                    'updated_at' => now(),
                    'oldImage' => $notification['oldImage']
                ], function ($value) {
                    return $value !== null && $value !== '';
                })
            ];
        }, $this->Notifications);
    }
}
