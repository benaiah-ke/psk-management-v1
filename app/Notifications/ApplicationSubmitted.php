<?php

namespace App\Notifications;

use App\Models\MembershipApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationSubmitted extends Notification
{
    use Queueable;

    public function __construct(public MembershipApplication $application) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Membership Application')
            ->greeting('Hello ' . $notifiable->first_name)
            ->line($this->application->user->full_name . ' has submitted a membership application for ' . $this->application->tier->name . '.')
            ->action('Review Application', url('/admin/applications/' . $this->application->id))
            ->line('Please review this application at your earliest convenience.');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'application_submitted',
            'title' => 'New Membership Application',
            'message' => $this->application->user->full_name . ' submitted a membership application for ' . $this->application->tier->name,
            'url' => '/admin/applications/' . $this->application->id,
            'application_id' => $this->application->id,
        ];
    }
}
