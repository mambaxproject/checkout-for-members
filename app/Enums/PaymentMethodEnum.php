<?php

namespace App\Enums;

enum PaymentMethodEnum: string
{
    case CREDIT_CARD = 'Cartão de Crédito';

    case BILLET = 'Boleto';

    case PIX = 'PIX';

    public static function getDescriptions(): array
    {
        return array_map(
            fn ($case) => ['value' => $case->name, 'name' => $case->value],
            self::cases()
        );
    }

    public static function getFromName(string $name)
    {
        return current(array_filter(self::getDescriptions(), fn ($r) => $r['value'] == $name))['name'] ?? "";
    }

}
