<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('category_id');
            $table->bigInteger('brand_id')->default(0);
            $table->bigInteger('subcategory_id');
            $table->bigInteger('fabric_id');
            $table->bigInteger('color_id');
            $table->bigInteger('section_id');
            $table->enum('wishlist',['0','1']);
            $table->string('product_name');
            $table->text('description');
            $table->text('more_info');
            $table->bigInteger('view_count')->default(0);
            $table->enum('status',['active','inactive']);
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
