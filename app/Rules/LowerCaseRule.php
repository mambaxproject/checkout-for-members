<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class LowerCaseRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        if (!preg_match('/[a-z]/', $value)) {
            $fail($attribute . ' deve conter pelo menos uma letra minúscula.');
        }

    }
}
