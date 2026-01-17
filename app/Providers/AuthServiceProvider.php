<?php

namespace App\Providers;

use App\Models\Address;
use App\Models\Design;
use App\Policies\AddressPolicy;
use App\Policies\DesignPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Address::class => AddressPolicy::class,
        Design::class => DesignPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
