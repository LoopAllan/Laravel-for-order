<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderMYR extends Model
{
    protected $table = 'orders_myr';
    protected $fillable = ['order_id', 'name', 'city', 'district', 'street', 'price'];
}
