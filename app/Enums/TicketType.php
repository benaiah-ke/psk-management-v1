<?php

namespace App\Enums;

enum TicketType: string
{
    case Complaint = 'complaint';
    case Suggestion = 'suggestion';
    case Inquiry = 'inquiry';
    case Support = 'support';
    case Other = 'other';

    public function label(): string
    {
        return match($this) {
            self::Complaint => 'Complaint',
            self::Suggestion => 'Suggestion',
            self::Inquiry => 'Inquiry',
            self::Support => 'Support',
            self::Other => 'Other',
        };
    }
}
