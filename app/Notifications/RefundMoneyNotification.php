<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RefundMoneyNotification extends Notification
{
    use Queueable;

    public string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        // Можно в env добавить почту можно через mail trap io
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Статус возврата')
            ->line($this->message);
    }
}
