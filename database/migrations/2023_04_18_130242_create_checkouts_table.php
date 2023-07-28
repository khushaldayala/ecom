<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkouts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('product_id');
            $table->string('option_id');
            $table->string('full_name');
            $table->bigInteger('phone');
            $table->text('address');
            $table->text('city');
            $table->text('state');
            $table->text('country');
            $table->bigInteger('zipcode');
            $table->text('billing_full_name');
            $table->bigInteger('billing_phone');
            $table->text('billing_address');
            $table->text('billing_city');
            $table->text('billing_state');
            $table->text('billing_coutry');
            $table->bigInteger('billing_zipcode');
            $table->text('order_comment');
            $table->string('order_id');
            $table->string('payment_status');
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
        Schema::dropIfExists('checkouts');
    }
}
