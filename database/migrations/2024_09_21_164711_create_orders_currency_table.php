<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersCurrencyTable extends Migration
{
    public function up()
    {
        Schema::create('orders_currency', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();
            $table->string('currency');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders_currency');
    }
}
