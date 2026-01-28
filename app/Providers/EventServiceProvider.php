<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\OrderCompleted;
use App\Listeners\GenerateInvoice;
use App\Models\Order;
use App\Models\Design;
use App\Observers\OrderObserver;
use App\Observers\DesignObserver;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        OrderCompleted::class => [
            GenerateInvoice::class,
        ],
    ];

    public function boot(): void
    {
        Order::observe(OrderObserver::class);
        Design::observe(DesignObserver::class);
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
