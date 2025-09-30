<?php

namespace App\Enums;

enum CustomNotificationTypeEnum: string
{
    case WHATSAPP = 'whatsapp';
    case EMAIL = 'email';
    case SMS = 'sms';

    public function label(): string
    {
        return match ($this) {
            self::WHATSAPP => 'WhatsApp',
            self::SMS => 'SMS',
            self::EMAIL => 'email',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function labels(): array
    {
        return array_map(fn($case) => $case->label(), self::cases());
    }

    public function getId(): int
    {
        return match ($this) {
            self::WHATSAPP => 1,
            self::EMAIL => 2,
            self::SMS => 3,
        };
    }
}
