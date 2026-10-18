<?php

namespace App\Enums;

enum VideoType: int
{
    case Subscription = 1;
    case Course = 2;

    public function label(): string
    {
        return match ($this) {
            self::Subscription => 'اشتراک',
            self::Course => 'دوره',
        };
    }
}
