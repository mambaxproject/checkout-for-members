<?php

namespace App\Enums;

enum SituationCoproducerEnum: string
{
    case PENDING  = 'PENDING';
    case ACTIVE   = 'ACTIVE';
    case CANCELED = 'CANCELED';

    public static function getDescription(mixed $value): string
    {
        return match ($value) {
            self::PENDING  => 'Pendente',
            self::ACTIVE   => 'Ativo',
            self::CANCELED => 'Recusado',
            default        => 'Pendente',
        };
    }

    public static function getClass(mixed $value): string
    {
        return match ($value) {
            self::PENDING  => 'text-warning-950',
            self::ACTIVE   => 'text-success-950',
            self::CANCELED => 'text-danger-950',
            default        => 'text-neutral-950',
        };
    }

    public static function getClassBackground(mixed $value): string
    {
        return match ($value) {
            self::PENDING  => 'bg-warning-200',
            self::ACTIVE   => 'bg-success-200',
            self::CANCELED => 'bg-danger-200',
            default        => 'bg-neutral-200',
        };
    }

}
