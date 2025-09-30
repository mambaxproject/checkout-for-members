<?php

namespace App\Http\Requests\Public\Subscription;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCreditCardRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'card.number'          => ['required', 'string', 'max:255'],
            'card.cardHolderName'  => ['required', 'string', 'min:13', 'max:19'],
            'card.expirationMonth' => ['required', 'min:1', 'max:12'],
            'card.expirationYear'  => ['required'],
            'card.cvv'             => ['required', 'digits:3', 'numeric', 'max:999'],
        ];
    }

    public function messages(): array
    {
        return [
            'card.number.required'          => 'O número do cartão é obrigatório.',
            'card.cardHolderName.required'  => 'O nome do titular do cartão é obrigatório.',
            'card.expirationMonth.required' => 'O mês de expiração do cartão é obrigatório.',
            'card.expirationYear.required'  => 'O ano de expiração do cartão é obrigatório.',
            'card.cvv.required'             => 'O CVV do cartão é obrigatório.',
            'card.number.min'               => 'O número do cartão deve ter pelo menos 13 caracteres.',
            'card.number.max'               => 'O número do cartão não pode exceder 19 caracteres.',
            'card.cardHolderName.max'       => 'O nome do titular do cartão não pode exceder 255 caracteres.',
            'card.expirationMonth.min'      => 'O mês de expiração deve ser pelo menos 1.',
            'card.expirationMonth.max'      => 'O mês de expiração deve ser no máximo 12.',
            'card.expirationYear.min'       => 'O ano de expiração deve ser pelo menos 2024.',
            'card.expirationYear.max'       => 'O ano de expiração deve ser no máximo 2099.',
            'card.cvv.digits'               => 'O CVV deve ter exatamente 3 dígitos.',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'card' => [
                'number'          => preg_replace('/\D/', '', $this->input('card.number')),
                'cardHolderName'  => trim($this->input('card.cardHolderName')),
                'expirationMonth' => (int) $this->input('card.expirationMonth'),
                'expirationYear'  => (int) $this->input('card.expirationYear'),
                'cvv'             => preg_replace('/\D/', '', $this->input('card.cvv')),
            ],
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }
}
