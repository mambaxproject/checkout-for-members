<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Support\Str;

class QuizHasOneCorrectOptionRule implements InvokableRule
{
    public function __invoke($attribute, $value, $fail)
    {
        $correctCount = 0;
        
        foreach ($value as $option) {
            if(array_key_exists('isCorrect', $option)){
                $correctCount++;
            }
        }
        
        if ($correctCount !== 1) {
            $questionIndex = Str::of($attribute)->before('.Options')->after('questions.');
            $fail("A pergunta #$questionIndex deve conter exatamente **uma** alternativa correta.");
        }
    }
}
