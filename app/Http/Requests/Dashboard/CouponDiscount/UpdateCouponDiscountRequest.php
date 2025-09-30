<?php

namespace App\Http\Requests\Dashboard\CouponDiscount;

use App\Models\Product;
use App\Rules\UniqueCouponFieldPerProductRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCouponDiscountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $product        = Product::find($this->product_id);
        $couponDiscount = $this->route('coupon_discount');

        return [
            'product_id' => [
                'required',
                'exists:products,id',
                'integer',
            ],
            'name' => [
                'string',
                'min:3',
                'max:30',
                'required',
                (new UniqueCouponFieldPerProductRule($product, 'name', $couponDiscount->id)),
            ],
            'code' => [
                'string',
                'required',
                'min:3',
                'max:30',
                (new UniqueCouponFieldPerProductRule($product, 'code', $couponDiscount->id)),
            ],
            'minimum_price_order' => [
                'required',
            ],
            'amount' => ['required', function ($attribute, $value, $fail) use ($product) {
                $calc  = $product->minPriceOffers * 0.8;
                $value = (float) str_replace(['.', ','], ['', '.'], $value);

                if (($this->type === 'PERCENTAGE' and $value > 80) or ($this->type === 'VALUE' and $value > $calc)) {
                    $fail('Valor do cupom inválido. O desconto máximo permitido é de 80%');
                }
            }],
            'start_at' => [
                'required',
            ],
            'end_at' => [
                'required',
                'after:start_at',
            ],
            'automatic_application' => [
                'nullable',
                'boolean',
            ],
            'once_per_customer' => [
                'nullable',
                'boolean',
            ],
            'newsletter_abandoned_carts' => [
                'nullable',
                'boolean',
            ],
            'only_first_order' => [
                'nullable',
                'boolean',
            ],
            'allow_affiliate_links' => [
                'nullable',
                'boolean',
            ],
            'offers'   => ['sometimes', 'array'],
            'offers.*' => ['required_with:offers', 'integer', 'exists:products,id'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'automatic_application'      => boolval($this->automatic_application),
            'once_per_customer'          => boolval($this->once_per_customer),
            'allow_affiliate_links'      => boolval($this->allow_affiliate_links),
            'newsletter_abandoned_carts' => boolval($this->newsletter_abandoned_carts),
            'only_first_order'           => boolval($this->only_first_order),
        ]);
    }

    public function messages(): array
    {
        return [
            'product_id.required'          => 'O campo produto é obrigatório.',
            'product_id.exists'            => 'O produto informado não existe.',
            'product_id.integer'           => 'O produto informado é inválido.',
            'name.string'                  => 'O campo nome deve ser uma string.',
            'name.min'                     => 'O campo nome deve ter no mínimo :min caracteres.',
            'name.max'                     => 'O campo nome deve ter no máximo :max caracteres.',
            'name.required'                => 'O campo nome é obrigatório.',
            'code.string'                  => 'O campo código deve ser uma string.',
            'code.min'                     => 'O campo código deve ter no mínimo :min caracteres.',
            'code.max'                     => 'O campo código deve ter no máximo :max caracteres.',
            'code.required'                => 'O campo código é obrigatório.',
            'amount.required'              => 'O campo valor é obrigatório.',
            'minimum_price_order.required' => 'O campo valor mínimo do pedido é obrigatório.',
            'start_at.required'            => 'O campo início é obrigatório.',
            'end_at.required'              => 'O campo fim é obrigatório.',
            'end_at.after'                 => 'O campo fim deve ser uma data posterior ao campo início.',
        ];
    }

}
