<?php
namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\OrderFactoryInterface;
use Illuminate\Support\Facades\Log;

class ProcessOrder
{
    protected $orderFactory;
    public function __construct(OrderFactoryInterface $orderFactory) 
    {
        $this->orderFactory = $orderFactory;
    }
    public function handle(OrderCreated $event)
    {
        $orderData = $event->data;
        $currency = $orderData['currency'];

        try {
            $orderModel = $this->orderFactory->create($currency);
            $orderModel->create([
                'order_id' => $orderData['id'],
                'name' => $orderData['name'],
                'city' => $orderData['address']['city'],
                'district' => $orderData['address']['district'],
                'street' => $orderData['address']['street'],
                'price' => $orderData['price'],
            ]);

            $orderCurrencyMode = $this->orderFactory->create_currency();
            $orderCurrencyMode->create([
                'order_id' => $orderData['id'],
                'currency' => $currency,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to process order', [
                'order_id' => $orderData['id'],
                'currency' => $currency,
                'error' => $e->getMessage(),
            ]);

        }
    }
}
