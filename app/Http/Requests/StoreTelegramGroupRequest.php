<?php

namespace App\Http\Requests;

use App\Services\Notification\Telegram\TelegramService;
use Illuminate\Foundation\Http\FormRequest;

class StoreTelegramGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'       => ['required', 'string'],
            'product_id' => ['required', 'exists:products,id', 'unique:telegram_groups,product_id'],
            'code'       => ['required', 'unique:telegram_groups'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $code = (new TelegramService)->generateUniqueCode();

        $this->merge(['code' => $code]);
    }
}
