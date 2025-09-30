<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Member\MemberRequestHelper;
use App\Models\Product;

class CreateCourseRelationRequest extends FormRequest
{
    use MemberRequestHelper;

    public function rules()
    {
        return [
            'courseId' => ['required', 'integer'],
            'offerId' => ['required', 'integer'],
            'productUuid' => ['required', 'string']
        ];
    }

    public function toArray(): array
    {
        return [
            'offerId' => $this->offerId,
            'productRef' => $this->productUuid
        ];
    }

    public function messages()
    {
        return [
            'offerId.required' => 'O campo oferta é obrigatório.',
            'productUuid'=> 'O campo produto é obrigatório.',
        ];
    }
}
