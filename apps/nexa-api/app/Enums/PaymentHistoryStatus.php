<?php

namespace App\Enums;

enum PaymentHistoryStatus: int
{
    case Pending = 0;
    case Paid = 1;
    case Failed = 2;

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'pending',
            self::Paid => 'paid',
            self::Failed => 'failed',
        };
    }
}
