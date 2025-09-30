<?php

namespace App\Enums;

enum ProductTypesEnum: int
{
    case DEFAULT = 1;
    case SUIT_MEMBERS = 2;

    public function label(): string
    {
        return match ($this) {
            self::DEFAULT => 'Default',
            self::SUIT_MEMBERS => 'Suit Members',
        };
    }
}