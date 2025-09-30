<?php

namespace App\Http\Requests\Dashboard;

use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAppRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $rulesMap = [
            'active-campaign' => [
                'name'    => ['required', 'string'],
                'api_url' => ['required', 'string'],
                'api_key' => ['required', 'string'],
            ],
            'google-tag-manager' => [
                'code_gtm' => ['required', 'string'],
            ],
            'google-analytics' => [
                'tracking_id' => ['required', 'string'],
            ],
            'member-kit' => [
                'name'       => ['required', 'string'],
                'secret_key' => ['required', 'string'],
                'class_id'   => ['required', 'string'],
            ],
            'reportana' => [
                'client_id'     => ['required', 'string'],
                'client_secret' => ['required', 'string'],
            ],
            'chat' => [
                'script_html_chat' => ['required', 'string'],
            ],
            'woocommerce' => [
                'store_url'       => ['required', 'string'],
                'consumer_key'    => ['required', 'string'],
                'consumer_secret' => ['required', 'string'],
                'skip_cart'       => ['required', 'boolean'],
            ],
            'botconversa' => [
                'apikey' => ['required', 'string'],
            ],
            'utmify' => [
                'api_token' => ['required', 'string'],
            ],
        ];

        $rules = $rulesMap[$this->app->slug] ?? [];

        return [
            'status' => ['required'],
            ...$rules,
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'skip_cart' => $this->skip_cart ? $this?->app?->slug === 'woocommerce' : null,
            'status'    => $this->status ?? StatusEnum::INACTIVE->name,
        ]);
    }

}
