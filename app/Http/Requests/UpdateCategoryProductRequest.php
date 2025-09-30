<?php

namespace App\Http\Requests;

use App\Models\CategoryProduct;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateCategoryProductRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('category_product_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
                'unique:category_products,name,' . request()->route('category_product')->id,
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
