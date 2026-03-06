<?php

namespace App\Enums;

enum MembershipStatus: string
{
    case Pending = 'pending';
    case Active = 'active';
    case Dormant = 'dormant';
    case Suspended = 'suspended';
    case Deceased = 'deceased';
    case Cancelled = 'cancelled';
    case Expired = 'expired';

    public function label(): string
    {
        return match($this) {
            self::Pending => 'Pending',
            self::Active => 'Active',
            self::Dormant => 'Dormant',
            self::Suspended => 'Suspended',
            self::Deceased => 'Deceased',
            self::Cancelled => 'Cancelled',
            self::Expired => 'Expired',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Pending => 'yellow',
            self::Active => 'green',
            self::Dormant => 'gray',
            self::Suspended => 'red',
            self::Deceased => 'gray',
            self::Cancelled => 'red',
            self::Expired => 'orange',
        };
    }
}
