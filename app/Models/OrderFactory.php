<?php
namespace App\Models;

class OrderFactory
{
    public static function create(string $currency)
    {
        switch ($currency) {
            case 'TWD':
                return new OrderTWD();
            case 'USD':
                return new OrderUSD();
            // 其他貨幣類似
            default:
                throw new \Exception('Unsupported currency');
        }
    }
}
