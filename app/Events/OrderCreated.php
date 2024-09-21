<?php
namespace App\Events;
use Illuminate\Foundation\Events\Dispatchable;


class OrderCreated
{
    use Dispatchable;
    public $data;
    public function __construct(array $orderData)
    {
        $this->data = $orderData;
    }
}
