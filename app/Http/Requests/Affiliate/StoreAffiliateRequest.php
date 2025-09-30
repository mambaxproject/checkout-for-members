<?php

namespace App\Http\Requests\Affiliate;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreAffiliateRequest extends FormRequest
{
    public function rules(): array
    {
        return [];
    }

    protected function failedValidation($validator)
    {
        $firstErrorMessage = $validator->errors()->first();

        throw new HttpResponseException(
            back()->with('error', $firstErrorMessage)
        );
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $affiliateEnabled     = boolval($this->product->getValueSchemalessAttributes('affiliate.enabled'));
            $productInMarketplace = boolval($this->product->getValueSchemalessAttributes('affiliate.showProductInMarketplace'));

            if (! $affiliateEnabled) {
                $validator->errors()->add('product', 'Este produto não está disponível para afiliação.');
            }

            if (request()->routeIs('dashboard.marketplace.joinAffiliate') && ! $productInMarketplace) {
                $validator->errors()->add('product', 'Este produto não está disponível no marketplace.');
            }

            if ($this->product->shop->owner_id === auth()->id()) {
                $validator->errors()->add('product', 'Você não pode se afiliar ao seu próprio produto.');
            }

            if ($this->product->affiliates()->where('user_id', auth()->id())->exists()) {
                $validator->errors()->add('product', 'Você já está afiliado a este produto.');
            }
        });
    }

    public function authorize(): bool
    {
        return auth()->check();
    }

}
