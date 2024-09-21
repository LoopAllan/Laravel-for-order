<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCurrency extends Model
{
    protected $table = 'orders_currency';
    protected $fillable = ['order_id', 'currency'];
}
