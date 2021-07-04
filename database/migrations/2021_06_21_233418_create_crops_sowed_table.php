<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCropsSowedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crops_sowed', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_profile_id');
            $table->foreign('customer_profile_id')->references('id')->on('customer_profile')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('crop_tag_cloud_id')->nullable();
            $table->foreign('crop_tag_cloud_id')->references('id')->on('tag_cloud')->onUpdate('cascade')->onDelete('cascade');
            $table->string('crop');
            $table->date('sowed_date')->nullable();
            $table->string('cropping_pattern')->nullable();
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
        Schema::drop('crops_sowed');
    }
}
