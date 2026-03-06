<?php

namespace App\Enums;

enum RenewalStatus: string
{
    case Pending = 'pending';
    case Invoiced = 'invoiced';
    case Paid = 'paid';
    case Overdue = 'overdue';
    case Waived = 'waived';

    public function label(): string
    {
        return match($this) {
            self::Pending => 'Pending',
            self::Invoiced => 'Invoiced',
            self::Paid => 'Paid',
            self::Overdue => 'Overdue',
            self::Waived => 'Waived',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Pending => 'gray',
            self::Invoiced => 'blue',
            self::Paid => 'green',
            self::Overdue => 'red',
            self::Waived => 'yellow',
        };
    }
}
