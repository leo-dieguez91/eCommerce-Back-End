<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderFailedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly Order $order,
        private readonly string $error
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->error()
            ->subject('Order Error #' . $this->order->id)
            ->line('An error occurred while processing your order.')
            ->line('Order number: ' . $this->order->id)
            ->line('Current status: ' . $this->order->status)
            ->line('Error: ' . $this->error)
            ->action('View order status', url('/tracking/' . $this->order->tracking_number));
    }
} 