<?php

namespace App\Http\Requests;

use App\Models\MaritalStatus;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateMaritalStatusRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('marital_status_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
                'unique:marital_statuses,name,' . request()->route('marital_status')->id,
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
