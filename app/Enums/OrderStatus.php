<?php

namespace App\Enums;

enum OrderStatus: int
{
    case Pending = 1;
    case Paid = 2;
    case Completed = 3;
    case Cancelled = 4;
    case Refunded = 5;

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Неоплачено',
            self::Paid => 'Оплачено',
            self::Completed => 'Виконано',
            self::Cancelled => 'Скасовано',
            self::Refunded => 'Повернено',
        };
    }
}
