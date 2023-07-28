<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('transaction_id');
            $table->string('product_id');
            $table->bigInteger('order_amount');
            $table->bigInteger('order_qty');
            $table->text('user_name');
            $table->bigInteger('phone');
            $table->text('shiping_address');
            $table->text('state');
            $table->text('city');
            $table->text('country');
            $table->bigInteger('zipcode');
            $table->string('place_type');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
