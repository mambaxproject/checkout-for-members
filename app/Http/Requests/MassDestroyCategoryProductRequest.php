<?php

namespace App\Http\Requests;

use App\Models\CategoryProduct;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyCategoryProductRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('category_product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:category_products,id',
        ];
    }
}
