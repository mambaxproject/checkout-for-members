<?php

namespace App\Enums;

enum StatusEnum: string
{
    case ACTIVE = 'Ativo';
    case INACTIVE = 'Inativo';

    public static function getDescriptions(): array
    {
        return array_map(
            fn($case) => ['value' => $case->name, 'name' => $case->value],
            self::cases()
        );
    }

    public static function getFromName(string $name)
    {
        return current(array_filter(self::getDescriptions(), fn($r) => $r['value'] == $name))['name'] ?? "";
    }

    public static function getClassText(string $name): string
    {
        return match ($name) {
            self::ACTIVE->name   => 'text-primary',
            self::INACTIVE->name => 'text-secondary',
            default              => 'text-secondary',
        };
    }

}
