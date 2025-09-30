<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AtLeastOneSpecialCharacterRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        if (!preg_match('/[^a-zA-Z\d]/', $value)) {
            $fail('The :attribute deve conter pelo menos um caractere especial.');
        }

    }
}
