<?php

namespace App\Http\Requests\Checkout;

use App\Enums\PaymentMethodEnum;
use App\Rules\CheckPFCNPJRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'shop_id'                                         => ['required', 'exists:shops,id'],
            'user.name'                                       => ['required', 'string', 'max:255'],
            'user.email'                                      => ['required', 'email', 'max:255'],
            'user.phone_number'                               => ['required', 'string', 'max:255'],
            'user.document_number'                            => ['required', 'string', 'max:255', (new CheckPFCNPJRule)],
            'items'                                           => ['required', 'array', 'min:1'],
            'items.*.product_id'                              => ['required_if:items.*.order_bump_id,null', 'exists:products,id', 'nullable'],
            'items.*.order_bump_id'                           => ['required_if:items.*.product_id,null', 'exists:order_bumps,id', 'nullable'],
            'items.*.quantity'                                => ['required', 'numeric', 'min:1'],
            'payment.paymentMethod'                           => ['required', Rule::in(array_column(PaymentMethodEnum::cases(), 'name'))],
            'payment.cardHolderName'                          => 'required_if:payment.paymentMethod,' . PaymentMethodEnum::CREDIT_CARD->name,
            'payment.cardNumber'                              => 'required_if:payment.paymentMethod,' . PaymentMethodEnum::CREDIT_CARD->name,
            'payment.cardExpiration'                          => ['required_if:payment.paymentMethod,' . PaymentMethodEnum::CREDIT_CARD->name, 'date_format:m/y', 'nullable'],
            'payment.cardCvv'                                 => ['required_if:payment.paymentMethod,' . PaymentMethodEnum::CREDIT_CARD->name, 'digits:3', 'numeric', 'max:999', 'nullable'],
            'payment.installments'                            => ['required_if:payment.paymentMethod,' . PaymentMethodEnum::CREDIT_CARD->name, 'numeric', 'min:1', 'max:12'],
            'payment.antifraudCode'                           => ['sometimes', 'string', 'max:255'],
            'affiliate_code'                                  => ['sometimes', 'string', 'exists:affiliates,code'],
            'cardToken'                                       => ['nullable', 'string'],
            'attributes.customField.nomeDeUsuarioSuitAcademy' => ['sometimes', 'string', function ($attribute, $value, $fail) {
                $response = Http::post(config('services.suitpay.base_url') . '/api/v1/academy/validate-account', [
                    'username' => $value,
                    'name'     => $this->user['name'],
                    'email'    => $this->user['email'],
                    'document' => preg_replace('/[^0-9]/', '', $this->user['document_number']),
                ]);

                if ($response->failed()) {
                    $fail('Esse username não está disponível. Digite outro.');
                }
            }],
        ];
    }

    protected function prepareForValidation(): void
    {
        $data = $this->toArray();

        $installmentsField = $data['payment']['installments'];

        data_set($data, 'attributes', array_merge(['utm' => $data['utm'] ?? []], $data['attributes'] ?? []));
        data_set($data, 'payment.installments', is_numeric($installmentsField) ? $installmentsField : 1);

        if (auth()->check() && $this->isNotFilled('shop_id')) {
            data_set($data, 'shop_id', auth()->user()?->shop()?->id);
        }

        $this->merge($data);
    }

    public function authorize(): bool
    {
        return true;
    }
}
