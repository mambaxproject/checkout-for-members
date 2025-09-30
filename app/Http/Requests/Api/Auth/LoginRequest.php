<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $username = $this->username();

        $rules = [
            $username => ['required', 'string', 'min:3', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'max:255']
        ];

        if ($this->isEmail($this->get('username'))) {
            $rules[$username] += [
                'email',
                'email:rfc,dns'
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'document_number.required' => 'O campo CPF é obrigatório.',
            'document_number.min' => 'O campo CPF deve ter no mínimo 3 caracteres.',
            'document_number.max' => 'O campo CPF deve ter no máximo 255 caracteres.',
            'document_number.email' => 'O campo CPF deve ser um e-mail válido.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.min' => 'O campo senha deve ter no mínimo 8 caracteres.',
            'password.max' => 'O campo senha deve ter no máximo 255 caracteres.',
        ];
    }

    public function getCredentials(): array
    {
        return $this->only($this->username(), 'password');
    }

    private function isEmail($param): bool
    {
        return filter_var($param, FILTER_VALIDATE_EMAIL);
    }

    private function username(): string
    {
        $username = 'email';

        if (request()->get('document_number'))
            $username = 'document_number';

        return $username;
    }

}
