<?php

namespace App\Http\Requests;

use App\Models\Affiliate;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreAffiliateRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('affiliate_create');
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
            ],
            'document_number' => [
                'string',
                'required',
            ],
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
