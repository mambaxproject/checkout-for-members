<?php

namespace App\Enums;

enum CRMEventTriggerEnum: string
{
    case PAID     = 'paid';
    case PENDING  = 'pending';
    case FAILED   = 'failed';
    case EXPIRED  = 'expired';
    case CANCELED = 'canceled';
    case NOTIFICATION = 'notification';
    case CONVERTED = 'converted';

    public static function getDescriptions(): array
    {
        return [
            ['value' => self::PAID->value, 'description' => 'Pago'],
            ['value' => self::PENDING->value, 'description' => 'Pendente'],
            ['value' => self::FAILED->value, 'description' => 'Falha'],
            ['value' => self::EXPIRED->value, 'description' => 'Expirado'],
            ['value' => self::CANCELED->value, 'description' => 'Cancelado'],
            ['value' => self::CONVERTED->value, 'description' => 'Convertido'],
        ];
    }

    public static function getOrderDescriptions(): array
    {
        return [
            ['value' => self::PAID->value, 'description' => 'Pago'],
            ['value' => self::PENDING->value, 'description' => 'Pendente'],
            ['value' => self::FAILED->value, 'description' => 'Falha'],
            ['value' => self::CANCELED->value, 'description' => 'Cancelado'],
        ];
    }

    public static function getAbandonedCartDescriptions(): array
    {
        return [
            ['value' => self::NOTIFICATION->value, 'description' => 'Notificação'],
            ['value' => self::EXPIRED->value, 'description' => 'Expirado'],
            ['value' => self::CONVERTED->value, 'description' => 'Convertido'],
        ];
    }

    public static function getDescription(mixed $value)
    {
        return match ($value) {
            self::PAID     => 'Pago',
            self::PENDING  => 'Pendente',
            self::FAILED   => 'Falha',
            self::EXPIRED  => 'Expirado',
            self::CANCELED => 'Cancelado',
            self::CONVERTED => 'Convertido',
            self::NOTIFICATION => 'Notificação',
        };
    }
}
