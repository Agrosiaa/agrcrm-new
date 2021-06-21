<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_profile', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mobile')->unique();
            $table->string('full_name');
            $table->string('communication_lang');
            $table->string('mother_tongue');
            $table->string('income_level');
            $table->string('cropping_pattern');
            $table->string('product_sold_market');
            $table->float('total_land');
            $table->boolean('use_microirrigation');
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
        Schema::drop('customer_profile');
    }
}
