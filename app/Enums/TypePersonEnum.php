<?php

namespace App\Enums;

enum TypePersonEnum: string
{
    case PERSON = 'person';
    case COMPANY = 'company';

    public static function getTranslation($value): string
    {
        return match ($value) {
            self::PERSON  => 'Pessoa física',
            self::COMPANY => 'Pessoa jurídica',
            default       => 'Pessoa física',
        };
    }
}
