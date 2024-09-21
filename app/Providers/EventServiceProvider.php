<?php

namespace App\Providers;

use App\Events\OrderCreated;
use App\Listeners\ProcessOrder;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        OrderCreated::class => [
            ProcessOrder::class,
        ],
    ];
    
}

