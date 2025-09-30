<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;

class ToggleClassRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function toArray(): array
    {
        return [
            'status' => (bool) $this->status
        ];
    }
}
