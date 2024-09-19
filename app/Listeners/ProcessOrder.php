<?php
namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\OrderFactory;

class ProcessOrder
{
    public function handle(OrderCreated $event)
    {
        $orderData = $event->orderData;
        $model = OrderFactory::create($orderData['currency']);
        $model->create([
            'order_id' => $orderData['id'],
            'name' => $orderData['name'],
            'city' => $orderData['address']['city'],
            'district' => $orderData['address']['district'],
            'street' => $orderData['address']['street'],
            'price' => $orderData['price'],
        ]);
    }
}
