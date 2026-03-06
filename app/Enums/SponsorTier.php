<?php

namespace App\Enums;

enum SponsorTier: string
{
    case Platinum = 'platinum';
    case Gold = 'gold';
    case Silver = 'silver';
    case Bronze = 'bronze';
    case Exhibitor = 'exhibitor';
    case Other = 'other';

    public function label(): string
    {
        return match($this) {
            self::Platinum => 'Platinum',
            self::Gold => 'Gold',
            self::Silver => 'Silver',
            self::Bronze => 'Bronze',
            self::Exhibitor => 'Exhibitor',
            self::Other => 'Other',
        };
    }
}
