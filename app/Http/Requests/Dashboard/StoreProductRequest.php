<?php

namespace App\Http\Requests\Dashboard;

use App\Enums\StatusEnum;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreProductRequest extends FormRequest
{
    public function rules(): array
    {
        $shopId = auth()->user()->shop()->id;

        return [
            'name' => [
                'string',
                'required',
                'min:3',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (Product::whereNull('parent_id')
                        ->where('name', $value)
                        ->exists()
                    ) {
                        $fail('Este nome já está em uso. Tente outro.');
                    }
                },
            ],
            'category_id' => ['required', 'integer']
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.string'   => 'O campo nome deve ser uma string.',
            'name.min'      => 'O campo nome deve ter no mínimo 3 caracteres.',
            'name.max'      => 'O campo nome deve ter no máximo 255 caracteres.',
            'name.unique'   => 'Este nome já está em uso. Tente outro.',
            'category_id.required' => 'O campo categoria é obrigatório.',
            'category_id.integer' => 'O campo categoria deve ser um número inteiro.',
        ];
    }

    protected function failedValidation($validator)
    {
        $firstErrorMessage = $validator->errors()->first();

        throw new HttpResponseException(
            back()->with('info', $firstErrorMessage)
        );
    }

    public function authorize(): bool
    {
        return auth()->check();
    }

    public function passedValidation(): void
    {
        $this->merge([
            'status' => $this->status ?? StatusEnum::ACTIVE->name,
        ]);
    }
}
