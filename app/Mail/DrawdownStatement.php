<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use Illuminate\Mail\Attachment;
use Illuminate\Contracts\Mail\Attachable;
class DrawdownStatement extends Mailable 
{
    use Queueable, SerializesModels;
    public $order;
    /**
     * Create a new message instance.
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $order = $this->order;
        $thTime = Carbon::parse($order->purchase_ymd)->addYears(543)->locale('th_TH')->isoFormat('D MMMM YYYY');
        $subject = "หนังสือเบิกใช้เงินกู้เลขที่ ".$order->order_number;
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.statement',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $order = $this->order;
        $file_name = "drawdown_statement-$order->order_number.pdf";
        $statement = Attachment::fromPath(storage_path('/app/public/statement')."/$file_name");
        return [$statement];
    }
}
