<?php

namespace App\Http\Requests\Dashboard\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        $userId = auth()->id();

        return [
            'shop.name' => ['required', 'string', 'max:255'],
            'shop.link' => ['sometimes', 'url', 'nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'shop.name.required' => 'O campo nome da loja é obrigatório.',
            'shop.name.string'   => 'O campo nome da loja deve ser uma string.',
            'shop.name.max'      => 'O campo nome da loja deve ter no máximo 255 caracteres.',
            'shop.link.url'      => 'O campo link da loja deve ser uma URL válida.',
            'shop.link.string'   => 'O campo link da loja deve ser uma string.',
            'shop.link.max'      => 'O campo link da loja deve ter no máximo 255 caracteres.',
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }

}
