<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTelegramGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'       => ['required', 'string'],
            'product_id' => ['required', 'exists:products,id', 'unique:telegram_groups,product_id,'.$this->telegram->id],
        ];
    }
}
