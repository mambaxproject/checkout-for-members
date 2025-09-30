<?php

namespace App\Http\Requests\Checkout;

use App\Enums\PaymentMethodEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PaymentUpSellRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'cardToken' => [
                'required',
                'string',
                'exists:orders,card_token_customer',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'cardToken.required' => 'O token do cartão é obrigatório.',
            'cardToken.exists'   => 'O token do cartão não está associado a nenhum pedido válido.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $order = $this->route('order');

        if ($order->card_token_customer) {
            $this->merge([
                'cardToken' => $order->card_token_customer,
            ]);
        }
    }

    protected function passedValidation(): void
    {
        $order = $this->route('order');

        $this->merge([
            'shop_id' => $order->shop_id,
            'user'    => [
                'name'            => $order->user->name,
                'email'           => $order->user->email,
                'phone_number'    => $order->user->phone_number,
                'document_number' => $order->user->document_number,
            ],
            'items' => [
                [
                    'product_id' => $this->route('upSell')->product_offer_id,
                    'quantity'   => 1,
                ],
            ],
            'payment' => [
                'paymentMethod' => PaymentMethodEnum::CREDIT_CARD->name,
                'installments'  => $order->payment->installments ?? 1,
                'cardToken'     => $this->input('cardToken'),
            ],
        ]);
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
        return true;
    }
}
