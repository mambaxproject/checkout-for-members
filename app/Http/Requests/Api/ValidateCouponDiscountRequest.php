<?php

namespace App\Http\Requests\Api;

use App\Enums\StatusEnum;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ValidateCouponDiscountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => [
                'string',
                'required',
                Rule::exists('coupons_discount', 'code')->where(function ($query) {
                    $query->whereRaw('NOW() BETWEEN start_at AND end_at')
                        ->where('status', StatusEnum::ACTIVE->name);
                }),
            ],
            'amount' => [
                'required',
                'gt:0',
            ],
            'product_id'        => ['required', 'exists:products,id'],
            'offer_id'          => ['required', 'exists:products,id'],
            'customer_email'    => ['string', 'nullable', 'email'],
            'is_affiliate_link' => ['boolean'],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'data'    => $validator->errors(),
        ], 422));
    }

    public function messages(): array
    {
        return [
            'code.required'     => 'Cupom inválido.',
            'code.exists'       => 'Cupom inválido ou expirado.',
            'amount.required'   => 'Cupom inválido.',
            'amount.gt'         => 'Cupom inválido.',
            'customer_document' => 'O campo CPF/CNPJ é obrigatório.',
        ];
    }

}
