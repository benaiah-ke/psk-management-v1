<?php

namespace App\Notifications;

use App\Models\MembershipApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationApproved extends Notification
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
            ->subject('Application Approved - Welcome to PSK!')
            ->greeting('Congratulations ' . $notifiable->first_name . '!')
            ->line('Your membership application for ' . $this->application->tier->name . ' has been approved.')
            ->line('Welcome to the Pharmaceutical Society of Kenya! We are excited to have you as a member.')
            ->action('View Membership', url('/portal/membership'))
            ->line('Thank you for joining PSK.');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'application_approved',
            'title' => 'Application Approved',
            'message' => 'Your membership application has been approved! Welcome to PSK.',
            'url' => '/portal/membership',
            'application_id' => $this->application->id,
        ];
    }
}
