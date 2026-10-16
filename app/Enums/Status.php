<?php

namespace App\Enums;

enum Status: int
{
    case Pending = 0;
    case Active = 1;
    case Inactive = 2;

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'درحال بررسی',
            self::Active => 'فعال',
            self::Inactive => 'غیرفعال',
        };
    }

}
