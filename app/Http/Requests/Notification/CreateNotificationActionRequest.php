<?php

namespace App\Http\Requests\Notification;

use App\Enums\CustomNotificationTypeEnum;
use App\Rules\Notification\NotificationActionNameRule;
use App\Rules\Notification\NotificationProductRule;
use App\Rules\Notification\UniqueEventNotificationIdsRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateNotificationActionRequest extends FormRequest
{

    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'nameAction' => ['required', 'string', 'max:255', new NotificationActionNameRule()],
            'productId' => ['required', 'integer', new NotificationProductRule()],
            'descAction' => ['string', 'nullable'],
            'type' => ['required', 'string', 'in:whatsapp,email,sms'],
            'Notifications' => ['required', 'array', new UniqueEventNotificationIdsRule()],
            'Notifications.*.eventId' => ['required', 'integer', 'max:5'],
            'Notifications.*.text' => ['string', 'nullable'],
            'Notifications.*.dispatchTime' => ['required', 'integer', 'min:0'],
            'Notifications.*.image' => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:2048', 'nullable'],
        ];
    }

    public function toArray(): array
    {
        return [
            'action' => [
                'name' => $this->nameAction,
                'description' => $this->descAction,
                'product_id' => $this->productId,
                'status' => true,
                'user_id' => Auth::user()->id
            ],
            'notifications' => $this->getNotifactions(),
        ];
    }

    private function getNotifactions(): array
    {
        return array_map(function ($notification) {
            return [
                'type_id' => CustomNotificationTypeEnum::from($this->type)->getId(),
                'event_id' => $notification['eventId'],
                'text_whatsapp' => $notification['text'],
                'dispatch_time' => $notification['dispatchTime'],
                'url_embed' => $notification['image'] ?? null,
                'status' => isset($notification['status']) ? true : false,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }, $this->Notifications);
    }
}
