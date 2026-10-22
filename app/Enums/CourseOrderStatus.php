<?php

namespace App\Enums;

enum CourseOrderStatus: int
{
    case PayWaiting = 0;
    case Completed = 1;
    case Canceled = 2;

    public function label(): string
    {
        return match ($this) {
            self::PayWaiting => 'درانتظار پرداخت',
            self::Completed => 'کامل شده',
            self::Canceled => 'لغو شده',
        };
    }
}
