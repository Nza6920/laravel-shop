<?php

namespace App\Providers;

use App\Events\OrderPaid;
use Illuminate\Support\Facades\Event;
use App\Listeners\RegisteredListener;
use App\Listeners\SendOrderPaidMail;
use Illuminate\Auth\Events\Registered;
use App\Listeners\UpdateProductSoldCount;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            RegisteredListener::class,
        ],

        OrderPaid::class => [
            UpdateProductSoldCount::class,
            SendOrderPaidMail::class,
        ],
    ];

    public function boot()
    {
        parent::boot();

        //
    }
}
