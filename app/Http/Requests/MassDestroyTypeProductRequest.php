<?php

namespace App\Http\Requests;

use App\Models\TypeProduct;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyTypeProductRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('type_product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:type_products,id',
        ];
    }
}
