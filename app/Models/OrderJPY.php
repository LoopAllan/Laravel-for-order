<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderJPY extends Model
{
    protected $table = 'orders_jpy';
    protected $fillable = ['order_id', 'name', 'city', 'district', 'street', 'price'];
}
