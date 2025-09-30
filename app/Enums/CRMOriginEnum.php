<?php

namespace App\Enums;

enum CRMOriginEnum: string
{
    case ABANDONED_CART = 'abandoned_cart';
    case ORDER          = 'order';
    case SUBSCRIPTION   = 'subscription';
    //case COMMISSIONING  = 'commissioning';

    public static function getDescription(mixed $value): string
    {
        return match ($value) {
            self::ABANDONED_CART => 'Carrinhos Abandonados',
            self::ORDER          => 'Vendas/Pedidos',
            self::SUBSCRIPTION   => 'Assinaturas',
            //self::COMMISSIONING  => 'Comissionamento'
        };
    }

    public static function getDescriptions(): array
    {
        return [
            ['value' => self::ABANDONED_CART->value, 'description' => 'Carrinhos Abandonados'],
            ['value' => self::ORDER->value,          'description' => 'Vendas/Pedidos'],
            ['value' => self::SUBSCRIPTION->value,   'description' => 'Assinaturas'],
            //['value' => self::COMMISSIONING->value,  'description' => 'Comissionamento'],
        ];
    }
}
