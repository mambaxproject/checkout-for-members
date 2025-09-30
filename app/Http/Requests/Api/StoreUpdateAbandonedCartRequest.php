<?php

namespace App\Http\Requests\Api;

use App\Models\AbandonedCart;
use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateAbandonedCartRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['sometimes', 'email', 'max:255'],
            'phone_number'   => ['sometimes', 'string', 'max:255'],
            'amount'         => ['required', 'gt:0'],
            'payment_method' => ['sometimes', 'string', 'max:255'],
            'product_id'     => ['required', 'exists:products,id'],
            'link_checkout'  => ['required', 'string'],
            'infosProduct'   => ['required', 'array'],
            'affiliate_code' => ['sometimes', 'string', 'exists:affiliates,code'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

}
