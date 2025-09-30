<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Member\MemberRequestHelper;

class CreateTrackRequest extends FormRequest
{
    use MemberRequestHelper;

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'courseId' => $this->courseId
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'O nome da trilha é obrigatória.',
        ];
    }
}
