<?php

namespace App\Enums;

enum EventType: string
{
    case Conference = 'conference';
    case Workshop = 'workshop';
    case Seminar = 'seminar';
    case Webinar = 'webinar';
    case AGM = 'agm';
    case Social = 'social';
    case Other = 'other';

    public function label(): string
    {
        return match($this) {
            self::Conference => 'Conference',
            self::Workshop => 'Workshop',
            self::Seminar => 'Seminar',
            self::Webinar => 'Webinar',
            self::AGM => 'AGM',
            self::Social => 'Social',
            self::Other => 'Other',
        };
    }
}
