<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntroScreensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intro_screens', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->text('image');
            $table->bigInteger('order');
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
        Schema::dropIfExists('intro_screens');
    }
}
