<?php
namespace App\Providers;

use App\Services\CurrencyService;
use App\Services\CurrencyServiceInterface;
use App\Models\OrderFactory;
use App\Models\OrderFactoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(CurrencyServiceInterface::class, CurrencyService::class);
        $this->app->bind(OrderFactoryInterface::class, OrderFactory::class);
    }
}
