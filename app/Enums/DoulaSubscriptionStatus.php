<?php

namespace App\Enums;

enum DoulaSubscriptionStatus: int
{
    case PayWaiting = 0;
    case MamaWaiting = 1;
    case MomWaiting = 2;
    case Completed = 3;
    case MomRejected = 4;
    case Canceled = 5;

    public function label(): string
    {
        return match ($this) {
            self::PayWaiting => 'درانتظار پرداخت',
            self::MamaWaiting => 'درانتظار تایید ماما',
            self::MomWaiting => ' درانتظار تایید مادر',
            self::Completed => 'کامل شده',
            self::MomRejected => 'ردشده از سمت مادر',
            self::Canceled => 'لغو شده',
        };
    }
}
