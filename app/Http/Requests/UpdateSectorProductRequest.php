<?php

namespace App\Http\Requests;

use App\Models\SectorProduct;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSectorProductRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('sector_product_edit');
    }

    public function rules()
    {
        return [
            'product_id' => [
                'required',
                'integer',
            ],
            'name' => [
                'string',
                'required',
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
