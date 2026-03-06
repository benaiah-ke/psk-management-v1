<?php

namespace App\Enums;

enum TicketStatus: string
{
    case Open = 'open';
    case InProgress = 'in_progress';
    case AwaitingResponse = 'awaiting_response';
    case Resolved = 'resolved';
    case Closed = 'closed';

    public function label(): string
    {
        return match($this) {
            self::Open => 'Open',
            self::InProgress => 'In Progress',
            self::AwaitingResponse => 'Awaiting Response',
            self::Resolved => 'Resolved',
            self::Closed => 'Closed',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Open => 'blue',
            self::InProgress => 'yellow',
            self::AwaitingResponse => 'orange',
            self::Resolved => 'green',
            self::Closed => 'gray',
        };
    }
}
