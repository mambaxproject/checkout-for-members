<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CapitalLetterRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        if (!preg_match('/[A-Z]/', $value)) {
            $fail($attribute . ' deve conter pelo menos uma letra maiúscula.');
        }

    }
}
