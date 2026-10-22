<?php

namespace App\Enums;

enum TransactionDirection: int
{
    case Credit = 1;
    case Debit = 2;
    case Internal = 3;

    public function label(): string
    {
        return match ($this) {
            self::Credit => 'افزایشی',
            self::Debit => 'کاهشی',
            self::Internal => 'داخلی',
        };
    }
}
