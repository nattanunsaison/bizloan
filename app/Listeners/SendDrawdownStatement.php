<?php

namespace App\Listeners;

use App\Events\DrawdownConfirmed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\DrawdownStatement;
use Illuminate\Support\Facades\Mail;

class SendDrawdownStatement
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
    public function handle(DrawdownConfirmed $event): void
    {
        $order = $event->order;
        $request = new \Illuminate\Http\Request();

        $request->replace(['id' => $order->id]);
        //first create PDF
        (new \App\Http\Controllers\BusinessLoanController)->downaloadDrawdownStatement($request);
        //second send email

        $statement = new DrawdownStatement($order); 

        $bcc = (new \App\Http\Controllers\HelperController())->getSSARoleUserId(9); //role.id = 9 = receipt
        $bcc = ['admin@siamsaison.com','paopan@siamsaison.com'];
        $emails =[auth()->user() ? auth()->user()->email : 'paopan@siamsaison.com'];
        Mail::to($emails)
            ->bcc($bcc)
            ->send($statement);
    }
}
