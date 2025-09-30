<?php

namespace App\Http\Requests\Dashboard\Subscription;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SendLinkUpdateCreditCardCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [];
    }

    protected function failedValidation($validator): void
    {
        $firstErrorMessage = $validator->errors()->first();

        throw new HttpResponseException(
            back()->with('info', $firstErrorMessage)
        );
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (! $this->route('order')->isSubscription()) {
                $validator->errors()->add('order', 'A assinatura não foi encontrada.');
            }

            $hasRecentRequestLinkUpdate = $this->route('order')->comments()
                ->where('created_at', '>=', now()->subDay())
                ->whereLike('comment', '%link de atualização de assinatura%')
                ->exists();

            if ($hasRecentRequestLinkUpdate) {
                $validator->errors()->add('order', 'Você já solicitou o link de atualização de assinatura nas últimas 24 horas.');
            }
        });
    }

}
