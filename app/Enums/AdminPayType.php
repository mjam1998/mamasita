<?php

namespace App\Enums;

enum AdminPayType: int
{
    case MamaShare = 1;
    case Refund = 2;

    public function label(): string
    {
        return match ($this) {
            self::MamaShare => 'سهم ماما',
            self::Refund => 'بازگشت وجه',
        };
    }
}
