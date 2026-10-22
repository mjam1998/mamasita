<?php

namespace App\Enums;

enum PlatformRevenueDirection: int
{
    case Credit = 1;
    case Debit = 2;

    public function label(): string
    {
        return match ($this) {
            self::Credit => 'افزایشی',
            self::Debit => 'کاهشی',
        };
    }
}
