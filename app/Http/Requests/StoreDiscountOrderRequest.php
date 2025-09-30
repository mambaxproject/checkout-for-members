<?php

namespace App\Http\Requests;

use App\Models\DiscountOrder;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreDiscountOrderRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('discount_order_create');
    }

    public function rules()
    {
        return [
            'order_id' => [
                'required',
                'integer',
            ],
            'discount_coupon_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
