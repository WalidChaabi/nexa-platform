<?php

namespace App\Enums;

enum UserRole: int
{
    case Admin = 1;
    case Owner = 2;
    case Employer = 3;

    public static function fromLegacy(int $isAdmin): self
    {
        return match ($isAdmin) {
            1 => self::Admin,
            2 => self::Owner,
            3 => self::Employer,
            default => throw new \InvalidArgumentException("Unknown role value: {$isAdmin}"),
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'admin',
            self::Owner => 'owner',
            self::Employer => 'employer',
        };
    }

    public function dashboardPath(): string
    {
        return match ($this) {
            self::Admin => '/admin',
            self::Owner => '/owner',
            self::Employer => '/employer',
        };
    }
}
