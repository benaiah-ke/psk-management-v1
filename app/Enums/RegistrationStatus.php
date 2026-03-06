<?php

namespace App\Enums;

enum RegistrationStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Cancelled = 'cancelled';
    case Waitlisted = 'waitlisted';
    case Attended = 'attended';
    case NoShow = 'no_show';

    public function label(): string
    {
        return match($this) {
            self::Pending => 'Pending',
            self::Confirmed => 'Confirmed',
            self::Cancelled => 'Cancelled',
            self::Waitlisted => 'Waitlisted',
            self::Attended => 'Attended',
            self::NoShow => 'No Show',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Pending => 'yellow',
            self::Confirmed => 'green',
            self::Cancelled => 'red',
            self::Waitlisted => 'blue',
            self::Attended => 'green',
            self::NoShow => 'gray',
        };
    }
}
