<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventReminder extends Notification
{
    use Queueable;

    public function __construct(public Event $event) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Event Reminder: ' . $this->event->title)
            ->greeting('Hello ' . $notifiable->first_name)
            ->line('This is a reminder that ' . $this->event->title . ' is coming up on ' . $this->event->start_date->format('D, d M Y \a\t h:i A') . '.');

        if ($this->event->venue_name) {
            $mail->line('Venue: ' . $this->event->venue_name);
        }

        if ($this->event->is_virtual && $this->event->virtual_link) {
            $mail->line('This is a virtual event.');
        }

        return $mail
            ->action('View Event', url('/portal/events/' . $this->event->id))
            ->line('We look forward to seeing you there!');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'event_reminder',
            'title' => 'Event Reminder',
            'message' => $this->event->title . ' starts on ' . $this->event->start_date->format('D, d M Y \a\t h:i A') . ($this->event->venue_name ? ' at ' . $this->event->venue_name : ''),
            'url' => '/portal/events/' . $this->event->id,
            'event_id' => $this->event->id,
        ];
    }
}
