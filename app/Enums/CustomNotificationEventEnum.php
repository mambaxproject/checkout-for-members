<?php

namespace App\Enums;

enum CustomNotificationEventEnum: int 
{
    case ABANDONEDCART = 1;
    case BOLETOPIXCREATED = 2;
    case BOLETOPIXPAYMENT = 3;
    case CARDPAYMENT = 4;
    case CARDPAYMENTERROR = 5;

    public static function getIds(): array
    {
        return array_map(fn(self $case) => $case->value, self::cases());
    }
}
