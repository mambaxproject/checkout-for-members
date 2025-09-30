<?php

namespace App\Http\Requests;

use App\Models\ItemOrder;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyItemOrderRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('item_order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:item_orders,id',
        ];
    }
}
