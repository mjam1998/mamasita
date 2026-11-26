<?php

namespace App\Enums;

enum Rate: int
{
    case Zero = 0;
    case One = 1;
    case Two = 2;
    case Three = 3;
    case Four = 4;
    case Five = 5;

    public function label(): string
    {
        return match ($this) {
            self::Zero => '0',
            self::One => '1',
            self::Two => '2',
            self::Three => '3',
            self::Four => '4',
            self::Five => '5',

        };
    }
}
