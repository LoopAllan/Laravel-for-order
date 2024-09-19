<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTWD extends Model
{
    protected $table = 'orders_twd';
    protected $fillable = ['order_id', 'name', 'city', 'district', 'street', 'price'];
}
