<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class CurrencyCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (preg_match('/^\d{1,3}(?:,\d{3})*(\.\d{2})?$|^\d+(\.\d{2})?$/', $value)) {
            return $value;
        }

        $value = preg_replace('/[^\d.,]/', '', $value);
        $value = str_replace(['.', ','], ['', '.'], $value);
        $value = number_format((float) $value, 2, '.', '');

        return $value;
    }

}
