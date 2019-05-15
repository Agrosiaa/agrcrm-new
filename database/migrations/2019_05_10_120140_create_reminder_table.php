<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReminderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reminder', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('call_back_id')->nullable();
            $table->foreign('call_back_id')->references('id')->on('call_back')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('customer_number_status_details_id')->nullable();
            $table->foreign('customer_number_status_details_id')->references('id')->on('customer_number_status_details')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamp('reminder_time')->nullable();
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
        Schema::drop('reminder');
    }
}
