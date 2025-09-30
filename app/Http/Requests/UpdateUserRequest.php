<?php

namespace App\Http\Requests;

use App\Models\User;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('user_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'email' => [
                'required',
                'unique:users,email,' . request()->route('user')->id,
            ],
            'phone_number' => [
                'string',
                'required',
                'unique:users,phone_number,' . request()->route('user')->id,
            ],
            'document_number' => [
                'string',
                'required',
                'unique:users,document_number,' . request()->route('user')->id,
            ],
            'birthday' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'roles.*' => [
                'integer',
            ],
            'roles' => [
                'required',
                'array',
            ],
        ];
    }
}
