<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class DomainLiveRule implements Rule
{

    public function passes($attribute, $value): bool
    {
        return Http::get($value)->ok();
    }

    public function message(): string
    {
        return 'O link que você está usando para que está fora do ar. Tenha certeza que ele está funcionando.';
    }

}
