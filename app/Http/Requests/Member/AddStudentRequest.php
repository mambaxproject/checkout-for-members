<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;

class AddStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'document' => ['required', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'O campo nome deve ser uma string.',
            'name.max' => 'O campo nome não pode ter mais que 255 caracteres.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.string' => 'O campo e-mail deve ser uma string.',
            'email.email' => 'O campo e-mail deve conter um e-mail válido.',
            'email.max' => 'O campo e-mail não pode ter mais que 255 caracteres.',
            'document.required' => 'O campo documento é obrigatório.',
            'document.string' => 'O campo documento deve ser uma string.',
            'document.max' => 'O campo documento não pode ter mais que 20 caracteres.',
        ];
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'document' =>  preg_replace('/\D/', '', $this->document),
            'offer' => $this->offer
        ];
    }
}
