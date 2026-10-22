<?php

namespace App\Enums;

enum PlatformRevenueType: int
{
    case OnlineVisit = 1;
    case MonthlySubscription = 2;
    case DoulaSubscription = 3;
    case VideoCourse = 4;
    case Refund = 5;

    public function label(): string
    {
        return match ($this) {
            self::OnlineVisit => 'ویزیت آنلاین',
            self::MonthlySubscription => 'اشتراک ماهیانه',
            self::DoulaSubscription => 'ماما همراه',
            self::VideoCourse => 'دوره ویدیویی',
            self::Refund => 'بازگشت وچه',
        };
    }
}
