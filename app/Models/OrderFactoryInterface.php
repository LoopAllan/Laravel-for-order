<?php
namespace App\Models;

interface OrderFactoryInterface 
{
    /**
     * Get model by currency
     * @param string $currency
     * @return mixed
     */
    public function create(string $currency);
    /**
     * Get model by order id. \
     * It will get currency from order_currency table first, then get model by currency
     * @param string $order_id
     * @return mixed
     */
    public function create_by_id(string $order_id);
    /**
     * Get order currency model instance.
     * @return mixed
     */
    public function create_currency();
}