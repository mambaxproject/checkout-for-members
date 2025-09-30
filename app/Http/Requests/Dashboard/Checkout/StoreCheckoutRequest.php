<?php

namespace App\Http\Requests\Dashboard\Checkout;

use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreCheckoutRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                Rule::unique('checkouts', 'name')->where('shop_id', user()->shop()->id),
                'string',
                'min:3',
                'max:255',
            ],
            'default' => [
                'boolean',
            ],
            'settings' => [
                'array'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'   => 'O campo nome é obrigatório.',
            'name.unique'     => 'O nome informado já está em uso.',
            'name.string'     => 'O nome informado é inválido.',
            'name.min'        => 'O nome informado é muito curto.',
            'name.max'        => 'O nome informado é muito longo.',
            'default.boolean' => 'O campo padrão é inválido.',
            'settings.array'  => 'O campo configurações é inválido.',
        ];
    }

    protected function failedValidation($validator): void
    {
        $firstErrorMessage = $validator->errors()->first();

        throw new HttpResponseException(
            back()
                ->withFragment('tab=tab-checkout')
                ->with('error', $firstErrorMessage)
        );
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'default' => $this->default === 'on',
        ]);
    }

    public function passedValidation(): void
    {
        $filteredSettings = array_filter($this->settings ?? [], fn($value) => !is_null($value));

        $this->merge([
            'status'   => $this->status ?? StatusEnum::ACTIVE->name,
            'settings' => $filteredSettings ?? [],
        ]);
    }

    public function authorize(): bool
    {
        return auth()->check();
    }

}
