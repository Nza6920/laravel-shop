<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Policies\UserAddressPolicy;
use App\Policies\OrderPolicy;
use App\Models\UserAddress;
use App\Models\Order;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        UserAddress::class => UserAddressPolicy::class,
        Order::class       => OrderPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
