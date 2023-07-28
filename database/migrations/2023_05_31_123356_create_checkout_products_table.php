<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckoutProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkout_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('checkout_id');
            $table->bigInteger('product_id');
            $table->bigInteger('filter_id');
            $table->bigInteger('filter_option_id');
            $table->text('product_name');
            $table->bigInteger('price');
            $table->bigInteger('qty');
            $table->bigInteger('total');
            $table->text('image');
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
        Schema::dropIfExists('checkout_products');
    }
}
