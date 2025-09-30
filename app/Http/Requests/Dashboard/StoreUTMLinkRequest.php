<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class StoreUTMLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'product_id'   => ['required', 'exists:products,id'],
            'utm_source'   => ['required', 'string'],
            'utm_medium'   => ['required', 'string'],
            'utm_campaign' => ['nullable', 'string'],
            'utm_content'  => ['nullable', 'string'],
            'utm_term'     => ['nullable', 'string'],
        ];
    }
}
