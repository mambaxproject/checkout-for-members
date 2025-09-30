<?php

namespace App\Http\Requests;

use App\Models\CouponDiscount;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyDiscountCouponRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('discount_coupon_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:discount_coupons,id',
        ];
    }
}
