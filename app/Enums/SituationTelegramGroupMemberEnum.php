<?php

namespace App\Enums;

enum SituationTelegramGroupMemberEnum: string
{
    case PENDING = 'PENDING';
    case ACTIVE = 'ACTIVE';

    public static function getDescription(mixed $value): string
    {
        return match ($value) {
            self::PENDING   => 'Pendente',
            self::ACTIVE    => 'Ativo',
            default         => 'Pendente',
        };
    }

    public static function getClass(mixed $value): string
    {
        return match ($value) {
            self::PENDING   => 'text-warning-400',
            self::ACTIVE    => 'text-success-400',
            default         => 'text-neutral-400',
        };
    }

    public static function getClassBackground(mixed $value): string
    {
        return match ($value) {
            self::PENDING   => 'bg-warning-200',
            self::ACTIVE    => 'bg-success-200',
            default         => 'bg-neutral-200',
        };
    }

}
