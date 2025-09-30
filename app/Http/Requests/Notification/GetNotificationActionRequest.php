<?php

namespace App\Http\Requests\Notification;

use Illuminate\Foundation\Http\FormRequest;

class GetNotificationActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'nameAction' => $this->query('nameAction'),
            'status' => $this->query('status'),
            'nameProduct' => $this->query('nameProduct')
        ]);
    }

    public function toArray(): array
    {
        $arrayDefault = [
            'name_action' => $this->nameAction,
            'name_product' => $this->nameProduct
        ];


        return array_filter(
            array_merge($arrayDefault, $this->getStatus()),
            fn($value) =>  $value !== null
        );
    }

    private function getStatus(): array
    {
        if ($this->status == 'active') {
            return ['status_action' => true];
        }

        if ($this->status == 'desactive') {
            return ['status_action' => false];
        }

        if ($this->status == 'removed') {
            return ['product_deleted_at' => true];
        }

        return [];
    }
}
