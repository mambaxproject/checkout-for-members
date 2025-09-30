<?php

namespace App\Http\Requests\Notification;

use App\Rules\Notification\NotificationActionNameRule;
use App\Rules\ProductBelongsToUserRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreNotificationActionRequest extends FormRequest
{

    public function authorize(): bool
    {
        return auth()->check();
    }

    public function prepareForValidation(): void
    {
        $this->merge(array_filter([
            'nameAction' => $this->query('nameAction'),
            'productId' => $this->query('productId'),
            'descAction' => $this->query('descAction'),
            'nameProduct' => $this->query('nameProduct'),
        ]));
    }

    public function rules()
    {
        return [
            'nameAction' => ['required', 'string', 'max:255', new NotificationActionNameRule()],
            'productId' => ['required', 'integer', new ProductBelongsToUserRule()],
            'descAction' => ['string', 'nullable'],
            'nameProduct' => ['required', 'string', 'max:255']
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'nameAction' => $this->nameAction,
            'productId' => $this->productId,
            'descAction' => $this->descAction,
            'nameProduct' => $this->nameProduct
        ]);
    }
}
