<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreDomainRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'domain' => [
                'required',
                'url',
                'string',
                'max:255',
                'unique:domains,domain',
            ],
            'dns' => ['array'],
        ];
    }

    public function messages(): array
    {
        return [
            'domain.required' => 'O domínio é obrigatório.',
            'domain.url'      => 'O domínio deve ser uma URL válida.',
            'domain.string'   => 'O domínio deve ser um texto.',
            'domain.max'      => 'O domínio deve ter no máximo 255 caracteres.',
            'domain.unique'   => 'Esse domínio já está cadastrado.',
        ];
    }

    public function prepareForValidation(): void
    {
        $parsedUrl = parse_url($this->domain);
        $host      = $parsedUrl['host'] ?? null;

        if ($host) {
            $rootDomain = str_ireplace('www.', '', $host);
            $url        = 'seguro.' . $rootDomain;

            $this->merge([
                'dns' => [
                    'url'   => $url,
                    'host'  => 'seguro',
                    'type'  => 'CNAME',
                    'value' => config('services.singularcdn.url'),
                ],
            ]);
        } else {
            throw ValidationException::withMessages([
                'domain' => 'URL de domínio inválida fornecida.',
            ]);
        }
    }

    public function authorize(): bool
    {
        return auth()->check();
    }

}
