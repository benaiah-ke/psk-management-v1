<?php

namespace App\Notifications;

use App\Models\TicketResponse;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TicketResponseReceived extends Notification
{
    use Queueable;

    public function __construct(public TicketResponse $ticketResponse) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'ticket_response',
            'title' => 'New Response on Ticket',
            'message' => 'New response on ticket ' . $this->ticketResponse->ticket->ticket_number . ': ' . \Illuminate\Support\Str::limit($this->ticketResponse->message, 80),
            'url' => '/portal/tickets/' . $this->ticketResponse->ticket_id,
            'ticket_id' => $this->ticketResponse->ticket_id,
        ];
    }
}
