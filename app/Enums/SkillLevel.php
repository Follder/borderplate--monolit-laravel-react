<?php

namespace App\Enums;

enum SkillLevel: int
{
    case Beginner = 1;
    case Novice = 2;
    case Intermediate = 3;
    case Pro = 4;

    public function label(): string
    {
        return match ($this) {
            self::Beginner => 'Початківець',
            self::Novice => 'Новачок',
            self::Intermediate => 'Умілий',
            self::Pro => 'Профі',
        };
    }

    /**
     * Перевіряє чи можуть два рівні займатись разом.
     * Можна тільки сусідні рівні.
     */
    public function isCompatibleWith(self $other): bool
    {
        return abs($this->value - $other->value) <= 1;
    }
}
