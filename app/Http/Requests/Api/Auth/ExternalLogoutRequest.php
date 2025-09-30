<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ExternalLogoutRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'username' => [
                'string',
                'required',
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'username é obrigatório.',
            'username.string'   => 'username inválido, o tipo precisa ser string.'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
