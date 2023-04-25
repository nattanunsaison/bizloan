<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeleteAmountConfirmed extends Notification
{
    use Queueable;
    private $count,$order_id;
    /**
     * Create a new notification instance.
     */
    public function __construct($count,$order_id)
    {
        $this->count = $count;
        $this->order_id = $order_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $count = $this->count;
        $order_id = $this->order_id;
        return (new MailMessage)
                    ->greeting("Dear Account Team")
                    ->line("There are $count receive histories deleted")
                    ->action('View deleted histories', url("/delete_receive_history?order_id=$order_id"))
                    ->line('Contact Collection Team for more detail!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
