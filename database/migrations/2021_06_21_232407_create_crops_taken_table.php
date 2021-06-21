<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCropsTakenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crops_taken', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_profile_id');
            $table->string('crop');
            $table->string('year');
            $table->string('month');
            $table->string('area');
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
        Schema::drop('crops_taken');
    }
}
