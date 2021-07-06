<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCropSprayingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crop_spraying', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_profile_id');
            $table->foreign('customer_profile_id')->references('id')->on('customer_profile')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('crop_sowed_id');
            $table->foreign('crop_sowed_id')->references('id')->on('crops_sowed')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('pesticide_tag_cloud_id');
            $table->foreign('pesticide_tag_cloud_id')->references('id')->on('tag_cloud')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('spraying_number');
            $table->date('spraying_date');
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
        Schema::drop('crop_spraying');
    }
}
