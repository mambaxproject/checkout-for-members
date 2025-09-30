<?php

namespace App\Http\Requests;

use App\Models\Gender;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreGenderRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('gender_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
                'unique:genders',
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
