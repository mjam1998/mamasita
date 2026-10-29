<?php

namespace App\Enums;

enum MamaInformationStatus: int
{
    case CompletingInformation = 0;
    case Pending = 1;
    case Active = 2;
    case Inactive = 3;
    case Rejected = 4;
    case NeedEdit = 5;

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'نیازمند بررسی',
            self::Active => 'فعال',
            self::Inactive => 'غیرفعال',
            self::CompletingInformation => 'درحال تکمیل اطلاعات',
            self::Rejected => 'رد شده',
            self::NeedEdit => 'نیازمند اصلاح',
        };
    }
}
