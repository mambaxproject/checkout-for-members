<?php

namespace App\Rules;

use App\Models\Product;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueCouponFieldPerProductRule implements ValidationRule
{

    public function __construct(
        protected Product $product,
        protected string $field,
        protected ?int $id = null,
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $coupon = $this->product->couponsDiscount()
            ->where($this->field, $value)
            ->when($this->id, fn($query) => $query->where('coupons_discount.id', '!=', $this->id))
            ->exists();

        if ($coupon) {

            $fields = [
                'name' => 'nome',
                'code' => 'código',
            ];

            $fail('Já existe um cupom com este '.$fields[$this->field].' neste produto. Por favor, escolha outro '.$fields[$this->field].'.');
        }
    }

}
