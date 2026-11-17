<?php

namespace App\Enums;

enum Sex: int
{
    case Male = 1;
    case Female = 2;

    public function label(): string
    {
        return match ($this) {
            self::Male => 'Чоловіча',
            self::Female => 'Жіноча',
        };
    }
}
