<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number'    => ['required', 'string', 'min:10', 'max:16', 'unique:users'],
            'document_number' => ['required', 'string', 'min:10', 'max:16', 'unique:users'],
            'birthday' => ['required', 'date', function ($attribute, $value, $fail) {
                $date = \DateTime::createFromFormat('Y-m-d', $value);
                $now  = new \DateTime();
                $age  = $now->diff($date)->y;

                if ($age < 18) {
                    $fail('Você deve ter no mínimo 18 anos de idade.');
                }
            }],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'            => 'O campo nome é obrigatório.',
            'name.string'              => 'O campo nome deve conter apenas letras.',
            'name.max'                 => 'O campo nome deve ter no máximo 255 caracteres.',
            'email.required'           => 'O campo e-mail é obrigatório.',
            'email.string'             => 'O campo e-mail deve deve conter apenas letras.',
            'email.email'              => 'O campo e-mail deve ser um e-mail válido.',
            'email.max'                => 'O campo e-mail deve ter no máximo 255 caracteres.',
            'email.unique'             => 'O e-mail informado já possui cadastro na plataforma.',
            'phone_number.required'    => 'O campo telefone é obrigatório.',
            'phone_number.string'      => 'O campo telefone deve ser uma string.',
            'phone_number.min'         => 'O campo telefone deve ter no mínimo 10 caracteres.',
            'phone_number.max'         => 'O campo telefone deve ter no máximo 16 caracteres.',
            'phone_number.unique'      => 'O telefone informado já está em uso.',
            'document_number.required' => 'O campo CPF é obrigatório.',
            'document_number.string'   => 'O campo CPF deve ser uma string.',
            'document_number.min'      => 'O campo CPF deve ter no mínimo 10 caracteres.',
            'document_number.max'      => 'O campo CPF deve ter no máximo 16 caracteres.',
            'document_number.unique'   => 'O CPF informado já possui cadastro na plataforma.',
            'password.required'        => 'O campo senha é obrigatório.',
            'password.string'          => 'O campo senha deve ser uma string.',
            'password.min'             => 'O campo senha deve ter no mínimo 8 caracteres.',
            'password.confirmed'       => 'O campo senha deve ser confirmado.',
        ];
    }

    protected function passedValidation(): void
    {
        $this->merge([
            'verified'          => true,
            'approved'          => true,
            'email_verified_at' => now(),
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }

}
