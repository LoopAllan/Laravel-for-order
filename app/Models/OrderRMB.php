<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRMB extends Model
{
    protected $table = 'orders_rmb';
    protected $fillable = ['order_id', 'name', 'city', 'district', 'street', 'price'];
}
