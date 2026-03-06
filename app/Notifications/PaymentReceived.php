<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReceived extends Notification
{
    use Queueable;

    public function __construct(public Payment $payment) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Payment Received - ' . $this->payment->invoice->invoice_number)
            ->greeting('Hello ' . $notifiable->first_name)
            ->line('We have received your payment of KES ' . number_format($this->payment->amount, 2) . ' for invoice ' . $this->payment->invoice->invoice_number . '.')
            ->action('View Invoice', url('/portal/invoices/' . $this->payment->invoice_id))
            ->line('Thank you for your payment.');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'payment_received',
            'title' => 'Payment Received',
            'message' => 'Payment of KES ' . number_format($this->payment->amount, 2) . ' received for invoice ' . $this->payment->invoice->invoice_number,
            'url' => '/portal/invoices/' . $this->payment->invoice_id,
            'invoice_id' => $this->payment->invoice_id,
            'payment_id' => $this->payment->id,
        ];
    }
}
