<?php

namespace App\Http\Requests\Dashboard;

use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreWebhookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'min:1',
                'max:255',
            ],
            'url' => [
                'required',
                'url',
                'min:1',
                'max:255',
            ],
            'event_id' => [
                'nullable',
                'array',
            ],
            'event_id.*' => [
                'exists:webhook_events,id',
            ],
            'status' => [
                'nullable',
                Rule::in(array_keys(StatusEnum::cases())),
            ],
            'product_id' => [
                'nullable',
                'array',
            ],
        ];
    }

    protected function failedValidation($validator)
    {
        $firstErrorMessage = $validator->errors()->first();

        throw new HttpResponseException(
            back()->with('error', $firstErrorMessage)
        );
    }
}
