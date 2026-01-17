<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Address;
use App\Policies\AddressPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         Gate::policy(Address::class, AddressPolicy::class);
        $locale = request()->header('Accept-Language', 'ar');
        $locale = substr($locale, 0, 2); // يخليها "ar" أو "en" فقط
        app()->setLocale($locale);

    }
}
