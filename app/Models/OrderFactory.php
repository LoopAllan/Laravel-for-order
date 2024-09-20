<?php
namespace App\Models;
use App;

class OrderFactory
{
    public static function create(string $currency)
    {
        $class = "App\Models\Order$currency";
        if (!class_exists($class)) throw new \Exception('Unsupported currency');
        return App::make($class);
    }
}
