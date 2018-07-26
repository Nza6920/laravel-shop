<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Policies\UserAddressPolicy;
use App\Models\UserAddress;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        UserAddress::class => UserAddressPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
