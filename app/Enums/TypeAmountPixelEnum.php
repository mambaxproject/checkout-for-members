<?php

namespace App\Enums;

enum TypeAmountPixelEnum: string
{
    case AMOUNT_TOTAL_WITH_FEE = 'Valor total (com juros)';
    case AMOUNT_TOTAL_PRODUCTS = 'Valor dos produtos';

    public static function getDescriptions(): array
    {
        return array_map(
            fn($case) => ['value' => $case->name, 'name' => $case->value],
            self::cases()
        );
    }

    public static function getFromName(string $name)
    {
        return current(array_filter(self::getDescriptions(), fn($r) => $r['value'] == $name))['name'];
    }

}
