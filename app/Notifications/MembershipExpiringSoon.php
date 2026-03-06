<?php

namespace App\Notifications;

use App\Models\Membership;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MembershipExpiringSoon extends Notification
{
    use Queueable;

    public function __construct(public Membership $membership) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Membership Expiring Soon')
            ->greeting('Hello ' . $notifiable->first_name)
            ->line('Your ' . $this->membership->tier->name . ' membership is expiring on ' . $this->membership->expiry_date->format('d M Y') . '.')
            ->line('Please renew your membership to continue enjoying PSK benefits.')
            ->action('Renew Membership', url('/portal/membership/renew'))
            ->line('If you have any questions, please contact our support team.');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'membership_expiring',
            'title' => 'Membership Expiring Soon',
            'message' => 'Your ' . $this->membership->tier->name . ' membership expires on ' . $this->membership->expiry_date->format('d M Y') . '. Please renew to continue.',
            'url' => '/portal/membership/renew',
            'membership_id' => $this->membership->id,
        ];
    }
}
