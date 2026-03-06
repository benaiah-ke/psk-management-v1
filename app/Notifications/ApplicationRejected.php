<?php

namespace App\Notifications;

use App\Models\MembershipApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationRejected extends Notification
{
    use Queueable;

    public function __construct(public MembershipApplication $application) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Membership Application Update')
            ->greeting('Hello ' . $notifiable->first_name)
            ->line('We regret to inform you that your membership application for ' . $this->application->tier->name . ' has not been approved.');

        if ($this->application->rejection_reason) {
            $mail->line('Reason: ' . $this->application->rejection_reason);
        }

        return $mail
            ->action('View Details', url('/portal/membership'))
            ->line('If you have questions, please contact our support team.');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'application_rejected',
            'title' => 'Application Not Approved',
            'message' => 'Your membership application has not been approved.' . ($this->application->rejection_reason ? ' Reason: ' . $this->application->rejection_reason : ''),
            'url' => '/portal/membership',
            'application_id' => $this->application->id,
        ];
    }
}
