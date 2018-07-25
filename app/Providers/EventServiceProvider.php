<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Listeners\RegisteredListener;
use Illuminate\Auth\Events\Registered;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            RegisteredListener::class,
        ],
    ];

    public function boot()
    {
        parent::boot();

        //
    }
}
