<?php

namespace App\Listeners;

use App\Events\ReceiveAmountConfirm;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ReceiveAmountConfirmed;

class SendReceiveAmountConfirmEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ReceiveAmountConfirm $event): void
    {
        //to send Email Notify Account that Receive Amount has Confirm
        $users = \App\Models\User::find(1);
        //$users = \App\Models\User::whereIn('id',[1,60])->get();
        //$users = ['paopan@siamsaison.com','thitikwan@siamsaison.com'];
        Notification::send($users, new ReceiveAmountConfirmed($event->record));
    }
}