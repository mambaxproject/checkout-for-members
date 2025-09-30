<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Member\MemberRequestHelper;

class AddDomainRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'url' => ['required', 'string', 'max:255', 'url'],
        ];
    }

    public function toArray(): array
    {
        return [
            'url' => $this->url
        ];
    }

    public function messages(): array
    {
        return [
            'url.required' => 'O campo url é obrigatório.',
            'url.string' => 'O campo url deve ser uma string.',
            'url.max' => 'O campo url não pode ter mais que 255 caracteres.',
            'url.url' => 'O domínio deve ser uma URL válida.',
        ];
    }
}
