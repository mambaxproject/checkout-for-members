<?php

namespace App\Enums;

enum PaymentStatusEnum: string
{
    case PAID = 'Pago';

    case PENDING = 'Pendente';

    case FAILED   = 'Falha';
    case CANCELED = 'Cancelado';
    case REFUNDED = 'Reembolsado';

    public static function getDescriptions(): array
    {
        return array_map(
            fn ($case) => ['value' => $case->name, 'name' => $case->value],
            self::cases()
        );
    }

    public static function getFromName(string $name)
    {
        $result = current(array_filter(self::getDescriptions(), fn ($r) => $r['value'] == $name));

        return $result ? $result['name'] : null;
    }

}
