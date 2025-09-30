<?php

namespace App\Http\Requests;

use App\Models\Affiliate;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateAffiliateRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('affiliate_edit');
    }

    public function rules()
    {
        return [
            'percentage' => [
                'numeric',
                'required',
            ],
            'start_at' => [
                'date',
                'nullable',
            ],
            'end_at' => [
                'required',
                'date',
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
