<?php

namespace App\Enums;

enum CallStatus: int
{
    case MamaWaiting = 0;
    case MomWaiting = 1;
    case Approved = 2;
    case Rejected = 3;

    public function label(): string
    {
        return match ($this) {
            self::MamaWaiting => 'درانتظار تماس ماما',
            self::MomWaiting => 'درانتظار تایید مادر',
            self::Approved => 'تایید شده',
            self::Rejected => 'رد شده',
        };
    }
}
