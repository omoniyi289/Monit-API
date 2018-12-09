<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
        'App\Events\PriceChangeExecutionGenerated' => [
          'App\Listeners\SendPriceChangeExecutionMail'
        ],
        'App\Events\PriceChangeApprovalGenerated' => [
          'App\Listeners\SendPriceChangeApprovalMail'
        ],
        'App\Events\ROPSGenerated' => [
          'App\Listeners\SendROPSMail'
        ],
         'App\Events\COPSGenerated' => [
          'App\Listeners\SendCOPSMail'
        ],
        'App\Events\VeloxPaymentRequestGenerated' => [
          'App\Listeners\SendVeloxPaymentApprovalMail'
        ],
         'App\Events\VeloxCreditLimitRequestGenerated' => [
          'App\Listeners\SendVeloxCreditLimitApprovalMail'
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
