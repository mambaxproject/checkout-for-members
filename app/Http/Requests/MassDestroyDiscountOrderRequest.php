<?php

namespace App\Http\Requests;

use App\Models\DiscountOrder;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyDiscountOrderRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('discount_order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:discount_orders,id',
        ];
    }
}
