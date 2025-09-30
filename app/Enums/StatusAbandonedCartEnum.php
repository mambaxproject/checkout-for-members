<?php

namespace App\Enums;

enum StatusAbandonedCartEnum: string
{
    case PENDING = 'pending';
    case CONVERTED = 'converted';
    case EXPIRED = 'expired';

    public static function getDescription(mixed $value): string
    {
        return match ($value) {
            self::PENDING   => 'Pendente',
            self::CONVERTED => 'Convertido',
            self::EXPIRED => 'Expirado',
            default         => 'Pendente',
        };
    }

    public static function getClass(mixed $value): string
    {
        return match ($value) {
            self::PENDING   => 'text-secondary',
            self::CONVERTED => 'text-primary',
            self::EXPIRED => 'text-danger-600',
            default               => 'text-secondary',
        };
    }

}
