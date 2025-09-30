<?php

namespace App\Http\Requests;

use App\Models\State;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateStateRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('state_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
                'unique:states,name,' . request()->route('state')->id,
            ],
            'code' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'uf' => [
                'string',
                'min:1',
                'max:2',
                'required',
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
