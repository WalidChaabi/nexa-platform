<?php

namespace App\Providers;

use App\Events\ActivateAccount;
use App\Events\DetailInvoice;
use App\Events\OrderEvent;
use App\Listeners\OrderListener;
use App\Events\OwnerEvent;
use App\Listeners\OwnerListener;
use App\Events\EmployEvent;
use App\Events\PaidEmail;
use App\Events\PaiedSms;
use App\Listeners\ActivateAccountListener;
use App\Listeners\DetailInvoiceListener;
use App\Listeners\EmployListener;
use App\Listeners\PaidEmailListener;
use App\Listeners\PaiedSmsListener;
use App\Models\Rdv;
use App\Observers\RdvObserver;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OwnerEvent::class => [
            OwnerListener::class,
        ],
        EmployEvent::class => [
            EmployListener::class,
        ],
        OrderEvent::class=>[
            OrderListener::class,
        ],
        PaiedSms::class=>[
            PaiedSmsListener::class,
        ],
        PaidEmail::class=>[
            PaidEmailListener::class,
        ]
        ,
        ActivateAccount::class=>[
            ActivateAccountListener::class,
        ],
        DetailInvoice::class=>[
            DetailInvoiceListener::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
        Rdv::observe(RdvObserver::class);
    }
}
