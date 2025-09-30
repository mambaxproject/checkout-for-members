<?php

namespace App\Enums;

enum TypeItemOrderEnum: string {
    case CART = 'Carrinho';

    case ORDER_BUMP = 'Order Bump';

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
