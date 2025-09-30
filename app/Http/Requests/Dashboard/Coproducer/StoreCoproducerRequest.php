<?php

namespace App\Http\Requests\Dashboard\Coproducer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCoproducerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc,dns', 'max:255'],
        ];
    }

    protected function failedValidation($validator)
    {
        $firstErrorMessage = $validator->errors()->first();

        throw new HttpResponseException(
            to_route('dashboard.products.edit', $this->product->client_product_uuid)
                ->withFragment('tab=tab-participations')
                ->with('error', $firstErrorMessage)
        );
    }

    protected function passedValidation(): void
    {
        if ($this->valid_until_at == 'lifetime') {
            $this->merge(['valid_until_at' => null, 'duration' => null]);
        } elseif (is_numeric($this->valid_until_at)) {
            $this->merge([
                'valid_until_at' => now()->addDays(intval($this->valid_until_at))->format('Y-m-d H:i:s'),
                'duration'       => $this->valid_until_at,
            ]);
        }
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->product->coproducers()->active()->count()) {
                $validator->errors()->add('email', 'Você só pode ter 1 coprodutor ativo por vez.');
            }

            if ($this->product->coproducers()->where('email', $this->email)->exists()) {
                $validator->errors()->add('email', 'Este e-mail já está cadastrado como coprodutor deste produto.');
            }

            if ($this->product->shop->owner->email === $this->email) {
                $validator->errors()->add('email', 'Você não pode ser coprodutor do seu próprio produto.');
            }

            if ($this->product->getValueSchemalessAttributes('emailSupport') == $this->email) {
                $validator->errors()->add('email', 'O e-mail do coprodutor não pode ser o mesmo email usado para suporte.');
            }
        });
    }

    public function authorize(): bool
    {
        return auth()->check();
    }

}
