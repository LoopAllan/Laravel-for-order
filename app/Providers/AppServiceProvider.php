<?php
namespace App\Providers;

use App\Services\CurrencyService;
use App\Services\CurrencyServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(CurrencyServiceInterface::class, CurrencyService::class);
    }
}
