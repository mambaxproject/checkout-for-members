<?php

namespace App\Enums;

enum MemberStatusEnum: string
{
    case pendente = 'pendente';
    case ativo = 'ativo';
    case rejeitado = 'rejeitado';
    case desativado = 'desativado';
    case rascunho = 'rascunho';
    
    public static function getClass(mixed $value): string
    {
        return match ($value) {
            self::pendente->value   => 'text-warning-800',
            self::ativo->value      => 'text-success-800',
            self::rejeitado->value  => 'text-red-800',
            self::desativado->value => 'text-neutral-500',
            self::rascunho->value   => 'text-info-800',
            default                 => 'text-neutral-400',
        };
    }

    public static function getClassBackground(mixed $value): string
    {
        return match ($value) {
            self::pendente->value   => 'bg-warning-200',
            self::ativo->value      => 'bg-success-200',
            self::rejeitado->value  => 'bg-red-200',
            self::desativado->value => 'bg-neutral-200',
            self::rascunho->value   => 'bg-info-200',
            default                 => 'bg-neutral-200',
        };
    }

    public static function getClassAdmin(mixed $value): string
    {
        return match ($value) {
            self::pendente->value   => 'bg-yellow-300 text-yellow-800',
            self::ativo->value      => 'bg-green-300 text-green-800',
            self::rejeitado->value  => 'bg-red-300 text-red-800',
            self::desativado->value => 'bg-gray-300 text-gray-800',
            self::rascunho->value   => 'bg-blue-300 text-blue-800',
            default                 => 'bg-gray-200 text-gray-400',
        };
    }

    public static function getDescriptions(): array
    {
        return [
            ['value' => self::pendente->value,   'description' => 'Pendente'],
            ['value' => self::ativo->value,      'description' => 'Ativo'],
            ['value' => self::rejeitado->value,  'description' => 'Rejeitado'],
            ['value' => self::desativado->value, 'description' => 'Desativado'],
            ['value' => self::rascunho->value,   'description' => 'Rascunho'],
        ];
    }
}
