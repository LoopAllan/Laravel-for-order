<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderUSD extends Model
{
    protected $table = 'orders_usd';
    protected $fillable = ['order_id', 'name', 'city', 'district', 'street', 'price'];
}
