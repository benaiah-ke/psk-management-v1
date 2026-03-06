<?php

namespace App\Enums;

enum InvoiceType: string
{
    case Membership = 'membership';
    case Event = 'event';
    case Subscription = 'subscription';
    case Proforma = 'proforma';
    case Other = 'other';

    public function label(): string
    {
        return match($this) {
            self::Membership => 'Membership',
            self::Event => 'Event',
            self::Subscription => 'Subscription',
            self::Proforma => 'Pro-forma',
            self::Other => 'Other',
        };
    }
}
