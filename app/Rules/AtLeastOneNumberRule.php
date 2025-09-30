<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AtLeastOneNumberRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        if (!preg_match('/[0-9]/', $value)) {
            $fail('The :attribute deve conter pelo menos um número.');
        }

    }
}
