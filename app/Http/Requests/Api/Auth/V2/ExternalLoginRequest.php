<?php

namespace App\Http\Requests\Api\Auth\V2;

use Illuminate\Foundation\Http\FormRequest;

class ExternalLoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => [
                'string',
                'required',
                'exists:users,email',
                'email:rfc,dns',
            ],
            '_token' => [
                'required',
                'string',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'  => 'Email é obrigatório.',
            'email.string'    => 'Email deve ser em texto.',
            'email.exists'    => 'Email não encontrado.',
            'email.email'     => 'Email inválido.',
            'email.email.rfc' => 'Email inválido.',
            'email.email.dns' => 'Email inválido.',
            '_token.required' => 'Token é obrigatório.',
            '_token.string'   => 'Token inválido.',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

}
