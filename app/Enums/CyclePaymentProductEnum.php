<?php

namespace App\Enums;

enum CyclePaymentProductEnum: string
{
    case FORTNIGHTLY = 'Pagamento quinzenal';
    case MONTHLY = 'Pagamento mensal';
    case QUARTERLY = 'Pagamento trimestral';
    case SEMI_ANNUAL = 'Pagamento semestral';

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
