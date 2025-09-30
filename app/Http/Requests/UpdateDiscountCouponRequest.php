<?php

namespace App\Http\Requests;

use App\Models\CouponDiscount;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateDiscountCouponRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('discount_coupon_edit');
    }

    public function rules()
    {
        return [
            'code' => [
                'string',
                'required',
                'unique:discount_coupons,code,' . request()->route('discount_coupon')->id,
            ],
            'amount' => [
                'required',
            ],
            'type' => [
                'required',
            ],
            'quantity' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'start_at' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
            'end_at' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
        ];
    }
}
