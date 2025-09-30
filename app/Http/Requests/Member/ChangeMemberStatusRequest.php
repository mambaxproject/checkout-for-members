<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Member\MemberRequestHelper;

class ChangeMemberStatusRequest extends FormRequest
{
    use MemberRequestHelper;

    public function rules()
    {
        return [];
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'email' => $this->email
        ];
    }

}
