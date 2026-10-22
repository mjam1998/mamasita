<?php

namespace App\Enums;

enum AdminPayStatus: int
{
    case Waiting = 0;
    case Paid = 1;
    case Canceled = 2;
    case Rejected = 3;

    public function label(): string
    {
        return match ($this) {
            self::Waiting => 'در حال بررسی',
            self::Paid => 'پرداخت شده',
            self::Canceled => 'کنسل',
            self::Rejected => 'رد شده',
        };
    }
}
