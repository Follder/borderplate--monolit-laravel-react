<?php

namespace App\Enums;

enum AdminRole: int
{
    case Admin = 1;
    case SuperAdmin = 2;

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Адміністратор',
            self::SuperAdmin => 'Супер адміністратор',
        };
    }
}
