<?php

namespace App\Http\Requests\Dashboard\Product;

use App\Enums\SituationProductEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\{Rule};

class UpdateSituationProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'situation' => [
                'required',
                Rule::in(array_column(SituationProductEnum::cases(), 'name')),
            ],
        ];
    }

    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            back()->with('error', $validator->errors()->first())
        );
    }

    public function messages(): array
    {
        return [
            'situation.required' => 'A situação do produto é obrigatória.',
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }
}
