<?php

namespace App\Listeners;

use App\Events\DeleteAmountConfirm;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\DeleteAmountConfirmed;

class SendDeleteAmountConfirmEmail
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
    public function handle(DeleteAmountConfirm $event): void
    {
        //$users = \App\Models\User::whereIn('id',[1,60])->get();
        //$users = \App\Models\User::find(1);
        $users = (new \App\Http\Controllers\HelperController)->getSSARoleUserId();
        Notification::send($users, new DeleteAmountConfirmed($event->count,$event->order_id));
    }
}
