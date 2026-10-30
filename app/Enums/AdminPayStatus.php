<?php

namespace App\Enums;

enum AdminPayStatus: int
{
    case Waiting = 0;
    case Paid = 1;
    case Canceled = 2;

    public function label(): string
    {
        return match ($this) {
            self::Waiting => 'در انتظار پرداخت',
            self::Paid => 'پرداخت شده',
            self::Canceled => 'کنسل',
        };
    }
}
