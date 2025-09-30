<?php

namespace App\Http\Requests\Dashboard\Checkout;

use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCheckoutRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'name' => [
                'string',
                'required',
                'min:3',
                'max:255',
            ],
            'default' => [
                'boolean',
            ],
            'settings' => [
                'array'
            ],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'default' => $this->default === 'on',
        ]);
    }

    public function passedValidation(): void
    {
        $filteredSettings = array_filter($this->settings, fn($value) => !is_null($value));

        $this->merge([
            'status'   => $this->status ?? StatusEnum::ACTIVE->name,
            'settings' => $filteredSettings,
        ]);
    }

    public function authorize(): bool
    {
        return auth()->check() && user()->shop()->checkouts->contains($this->checkout);
    }

}
