<?php

namespace App\Http\Requests\Checkout;

use App\Models\Shop;
use Illuminate\Foundation\Http\FormRequest;

class CardInstallmentsRequest extends FormRequest
{
    public Shop $shop;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shop_id' => [
                'required',
                'integer',
                'exists:shops,id',
                fn ($attribute, $value, $fail) => $this->validateShop($value, $fail),
            ],
            'value' => ['required', 'numeric', 'min:1'],
        ];
    }

    private function validateShop($value, $fail): void
    {
        $this->shop = Shop::find($value, ['client_id_banking', 'client_secret_banking']);

        if (! $this->shop || empty($this->shop->client_id_banking) || empty($this->shop->client_secret_banking)) {
            $fail('The selected shop is invalid or missing required banking credentials.');
        }
    }

    protected function prepareForValidation(): void
    {
        if (auth()->check() && $this->isNotFilled('shop_id')) {
            $this->merge([
                'shop_id' => auth()->user()?->shop()?->id,
            ]);
        }
    }
}
