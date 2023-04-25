<?php

namespace App\Listeners;

use App\Events\ReceiveAmountConfirm;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreatePaybackSupplierStatement
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
        $record = $event->record;
        $controller = new \App\Http\Controllers\ReportController();
        $controller->exportDealerStatement($record);
    }
}
