<?php

namespace App\Enums;

enum InvoiceStatus: int
{
    case Pending = 0;
    case Cancelled = 2;
    case Returned = 4;

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'pending',
            self::Cancelled => 'cancelled',
            self::Returned => 'returned',
        };
    }
}
