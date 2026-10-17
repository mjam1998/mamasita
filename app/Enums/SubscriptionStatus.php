<?php

namespace App\Enums;

enum SubscriptionStatus: int
{
    case PayWaiting = 0;
    case Active = 1;
    case Completed = 2;
    case Canceled = 3;

    public function label(): string
    {
        return match ($this) {
            self::PayWaiting => 'درانتظار پرداخت',
            self::Active => 'فعال',
            self::Completed => 'کامل شده',
            self::Canceled => 'لغو شده',
        };
    }
}
