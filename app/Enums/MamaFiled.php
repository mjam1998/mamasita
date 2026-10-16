<?php

namespace App\Enums;

enum MamaFiled: int
{
    case OnlineVisit = 1;
    case MonthlySubscription = 2;
    case DoulaSubscription = 3;

    public function label(): string
    {
        return match ($this) {
            self::OnlineVisit => 'ویزیت آنلاین',
            self::MonthlySubscription => 'اشتراک ماهانه',
            self::DoulaSubscription => 'همراه زایمان',
        };
    }
}
