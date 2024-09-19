<?php
namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class OrderCreated
{
    use Dispatchable;

    public $orderData;

    public function __construct(array $orderData)
    {
        $this->orderData = $orderData;
    }
}
