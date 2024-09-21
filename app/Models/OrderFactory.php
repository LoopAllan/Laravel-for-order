<?php
namespace App\Models;

use App;
use App\Services\CurrencyServiceInterface;

class OrderFactory
{
    protected $currencyService;

    public function __construct(CurrencyServiceInterface $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function create(string $currency)
    {
        if (!$this->currencyService->isSupportedCurrency($currency)) {
            throw new \InvalidArgumentException('Unsupported currency');
        }
        $class = "App\Models\Order$currency";
        return App::make($class);
    }

    public function create_by_id(string $id)
    {
        $model = $this->create_currency();

        $order = $model->where('order_id', $id)->first();

        if (!$order) throw new \InvalidArgumentException('Order '.$id.' not found');

        $currency = $order->currency;

        $class = "App\Models\Order$currency";

        if (!class_exists($class)) throw new \InvalidArgumentException('Unsupported currency');

        return App::make($class);
    }

    public function create_currency()
    {
        return App::make(OrderCurrency::class);
    }
}
