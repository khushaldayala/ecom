<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id');
            $table->bigInteger('variant_id');
            $table->bigInteger('variant_option_id');
            $table->enum('discount_type',['percentage','price'])->nullable();
            $table->bigInteger('off_price')->nullable();
            $table->bigInteger('off_percentage')->nullable();
            $table->bigInteger('original_price')->nullable();
            $table->bigInteger('discount_price')->nullable();
            $table->bigInteger('qty');
            $table->text('sku');
            $table->bigInteger('weight');
            $table->string('color_code');
            $table->enum('status',['active','inactive']);
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
        Schema::dropIfExists('product_variants');
    }
}
