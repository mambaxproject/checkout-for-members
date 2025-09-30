<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class WebhookTransationSuitpayRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'idTransaction' => [
                'required',
                'string',
            ],
            'recurrencyId' => [
                'nullable',
                'string',
            ],
            'typeTransaction' => [
                'required',
                'string',
            ],
            'statusTransaction' => [
                'required',
                'string',
            ],
            'netAmount' => [
                'nullable',
            ],
        ];
    }

}
