<?php

namespace App\Http\Requests\Dashboard;

use App\Enums\StatusEnum;
use App\Rules\DomainLiveRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWebhookRequest extends FormRequest
{

    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'min:1', 'max:255'],
            'url' => [
                'required',
                'url',
                'min:1',
                'max:255',
                new DomainLiveRule(),
            ],
            'event_id' => ['nullable', 'array'],
            'event_id.*' => [
                'exists:webhook_events,id',
            ],
            'status' => ['nullable', Rule::in(array_keys(StatusEnum::cases()))],
        ];
    }
}
