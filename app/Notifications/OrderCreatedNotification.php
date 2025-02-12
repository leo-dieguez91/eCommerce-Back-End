<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private Order $order
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Order Created #' . $this->order->id)
            ->line('Your order has been created successfully.')
            ->line('Total: $' . $this->order->total_amount)
            ->line('Status: ' . $this->order->status)
            ->line('Tracking number: ' . $this->order->tracking_number)
            ->action('Track my order', url('/tracking/' . $this->order->tracking_number))
            ->line('Thank you for your purchase!');
    }
} 