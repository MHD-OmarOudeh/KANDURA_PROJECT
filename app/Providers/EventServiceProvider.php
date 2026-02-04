<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

// Events
use App\Events\OrderCreated;
use App\Events\OrderCompleted;
use App\Events\OrderStatusChanged;
use App\Events\DesignCreated;

// Listeners
use App\Listeners\GenerateInvoice;
use App\Listeners\SendOrderCreatedNotification;
use App\Listeners\SendOrderStatusChangedNotification;
use App\Listeners\SendDesignCreatedNotification;

// Observers
use App\Models\Order;
use App\Models\Design;
use App\Observers\OrderObserver;
use App\Observers\DesignObserver;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        // Order Events
        OrderCreated::class => [
            SendOrderCreatedNotification::class,
        ],
        OrderStatusChanged::class => [
            SendOrderStatusChangedNotification::class,
        ],
        OrderCompleted::class => [
            GenerateInvoice::class,
        ],

        // Design Events
        DesignCreated::class => [
            SendDesignCreatedNotification::class,
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
