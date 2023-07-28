<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('gender')->nullable();
            $table->text('image')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->bigInteger('phone_number');
            $table->integer('otp')->nullable();
            $table->enum('status',['inactive','active']);
            $table->rememberToken();
            $table->string('version')->nullable();
            $table->text('fcm_token')->nullable();
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
        Schema::dropIfExists('users');
    }
}
