<?php

namespace App\Http\Requests\Notification;

use App\Rules\Notification\NotificationActionNameRule;
use App\Rules\ProductBelongsToUserRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DuplicateNotificationActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'nameAction' => ['required', 'string', 'max:255', new NotificationActionNameRule()],
            'productId' => ['required', 'integer', new ProductBelongsToUserRule()],
            'descAction' => ['string', 'nullable'],
            'actionId' => ['required', 'integer'],
        ];
    }

    public function toArray(): array
    {
        return [
            'action' => array_filter([
                'name' => $this->nameAction,
                'description' => $this->descAction,
                'product_id' => $this->productId,
                'status' => true,
                'user_id' => Auth::user()->id
            ]),
            'duplicateActionId' => $this->actionId
        ];
    }
}
