<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ExternalLoginRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'document_number' => [
                'string',
                'required',
                'exists:users,document_number',
            ],
            '_token' => [
                'required',
                'string',
            ],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'document_number' => preg_replace('/[^0-9]/', '', $this->input('document_number')),
        ]);
    }

    public function messages(): array
    {
        return [
            'document_number.required' => 'Nº documento é obrigatório.',
            'document_number.string'   => 'Nº documento inválido.',
            'document_number.exists'   => 'Nº documento não encontrado.',
            '_token.required'          => 'Token é obrigatório.',
            '_token.string'            => 'Token inválido.',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

}
