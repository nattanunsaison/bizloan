<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\ReceiveAmountConfirm;
use App\Listeners\SendReceiveAmountConfirmEmail;
use App\Listeners\CreatePaybackSupplierStatement;
use App\Events\DeleteAmountConfirm;
use App\Listeners\SendDeleteAmountConfirmEmail;
use App\Events\DrawdownConfirmed;
use App\Listeners\SendDrawdownStatement;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        ReceiveAmountConfirm::class => [
            SendReceiveAmountConfirmEmail::class,
            CreatePaybackSupplierStatement::class,
        ],

        DeleteAmountConfirm::class => [
            SendDeleteAmountConfirmEmail::class,
        ],

        DrawdownConfirmed::class=>[
            SendDrawdownStatement::class,
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
