<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_schedules', function (Blueprint $table) {
            $table->id();
            $table->text('schedule_title');
            $table->date('date');
            $table->string('notification_title');
            $table->text('message');
            $table->bigInteger('sender_id');
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
        Schema::dropIfExists('notification_schedules');
    }
}
