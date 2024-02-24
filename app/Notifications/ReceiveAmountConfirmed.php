<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReceiveAmountConfirmed extends Notification
{
    use Queueable;
    public $record;

    /**
     * Create a new notification instance.
     */
    public function __construct($record)
    {
        $this->record = $record;
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
        $record = $this->record;
        //dd($record);
        return (new MailMessage)
                    ->line('Receive amount has been confirm for order ID: '.$record->order_id)
                    ->line('Click link below to proceed futher!')
                    ->action('See drafted documents', config('app.url').'/buyer_receipt?id='.$record->id.'&mode=view');
                    
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
