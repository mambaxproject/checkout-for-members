<?php

namespace App\Http\Requests;

use App\Models\CategoryProduct;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCategoryProductRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('category_product_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
                'unique:category_products',
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
