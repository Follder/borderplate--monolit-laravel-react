<?php

namespace App\Enums;

enum SeasonType: int
{
    case Low = 1;
    case High = 2;
    case Holiday = 3;

    public function label(): string
    {
        return match ($this) {
            self::Low => 'Низький сезон',
            self::High => 'Високий сезон',
            self::Holiday => 'Святковий сезон',
        };
    }
}
