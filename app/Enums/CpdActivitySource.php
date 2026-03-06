<?php

namespace App\Enums;

enum CpdActivitySource: string
{
    case Event = 'event';
    case Manual = 'manual';
    case External = 'external';
    case Auto = 'auto';

    public function label(): string
    {
        return match($this) {
            self::Event => 'Event Attendance',
            self::Manual => 'Manual Entry',
            self::External => 'External',
            self::Auto => 'Automatic',
        };
    }
}
