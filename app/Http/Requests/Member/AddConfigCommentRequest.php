<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;

class AddConfigCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'visibility' => ['required', 'string'],
        ];
    }

    public function toArray(): array
    {
        return [
            'visibility' => $this->visibility
        ];
    }
}
