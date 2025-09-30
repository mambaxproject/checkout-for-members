<?php

namespace App\Enums;

enum SituationProductEnum: string
{
    case DRAFT       = 'draft';
    case PENDING     = 'pending';
    case IN_ANALYSIS = 'in_analysis';
    case PUBLISHED   = 'published';
    case PAUSED      = 'paused';
    case REPROVED    = 'reproved';
    case DISABLE     = 'disable';

    public static function getDescription(mixed $value): string
    {
        return match ($value) {
            self::DRAFT->name       => 'Rascunho',
            self::PENDING->name     => 'Pendente',
            self::IN_ANALYSIS->name => 'Em análise',
            self::PUBLISHED->name   => 'Publicado',
            self::PAUSED->name      => 'Pausado',
            self::REPROVED->name    => 'Reprovado',
            self::DISABLE->name     => 'Desativado',
            default                 => 'Pendente',
        };
    }

    public static function getClass(mixed $value): string
    {
        return match ($value) {
            self::DRAFT->name       => 'text-info-800',
            self::PENDING->name     => 'text-warning-800',
            self::IN_ANALYSIS->name => 'text-warning-800',
            self::PUBLISHED->name   => 'text-success-800',
            self::PAUSED->name      => 'text-neutral-500',
            self::REPROVED->name    => 'text-red-800',
            self::DISABLE->name     => 'text-red-700',
            default                 => 'text-neutral-400',
        };
    }

    public static function getClassBackground(mixed $value): string
    {
        return match ($value) {
            self::DRAFT->name       => 'bg-info-200',
            self::PENDING->name     => 'bg-warning-200',
            self::IN_ANALYSIS->name => 'bg-warning-200',
            self::PUBLISHED->name   => 'bg-success-200',
            self::PAUSED->name      => 'bg-neutral-200',
            self::REPROVED->name    => 'bg-danger-200',
            self::DISABLE->name     => 'bg-danger-200',
            default                 => 'bg-neutral-400',
        };
    }

    public static function productSituationGetClassAdmin(mixed $value): string
    {
        return match ($value) {
            self::DRAFT->name       => 'bg-blue-200 text-blue-800',
            self::PENDING->name     => 'bg-yellow-200 text-yellow-800',
            self::IN_ANALYSIS->name => 'bg-yellow-200 text-yellow-800',
            self::PUBLISHED->name   => 'bg-green-200 text-green-800',
            self::PAUSED->name      => 'bg-gray-200 text-gray-500',
            self::REPROVED->name    => 'bg-red-200 text-red-800',
            self::DISABLE->name     => 'bg-red-400 text-red-800',
            default                 => 'bg-gray-200 text-gray-400',
        };
    }

    public static function getClassAdmin(mixed $value): string
    {
        return match ($value) {
            self::DRAFT->name     => 'bg-light text-dark',
            self::PENDING->name   => 'bg-yellow-300 text-yellow-800',
            self::PUBLISHED->name => 'bg-green-300 text-green-800',
            self::PAUSED->name    => 'bg-yellow-300 text-yellow-800',
            self::REPROVED->name  => 'bg-red-300 text-red-800',
            self::DISABLE->name   => 'bg-red-300 text-red-800',
            default               => 'bg-light text-dark',
        };
    }

    public static function getDescriptions(): array
    {
        return [
            ['value' => self::DRAFT->name, 'description' => 'Rascunho'],
            ['value' => self::PENDING->name, 'description' => 'Pendente'],
            ['value' => self::IN_ANALYSIS->name, 'description' => 'Em análise'],
            ['value' => self::PUBLISHED->name, 'description' => 'Publicado'],
            ['value' => self::PAUSED->name, 'description' => 'Pausado'],
            ['value' => self::REPROVED->name, 'description' => 'Reprovado'],
            ['value' => self::DISABLE->name, 'description' => 'Desativado'],
        ];
    }

}
