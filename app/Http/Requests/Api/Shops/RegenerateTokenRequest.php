<?php

namespace App\Http\Requests\Api\Shops;

use Illuminate\Foundation\Http\FormRequest;

class RegenerateTokenRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'exists:users,email',
            ],
            'document_number' => [
                'string',
                'required',
                'exists:users,document_number',
            ],
            'username_banking' => [
                'nullable',
                'string',
                'exists:shops,username_banking',
            ],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'document_number' => preg_replace('/[^0-9]/', '', $this->input('document_number'))
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }

}
