<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesChatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_chat', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('crm_customer_id');
            $table->foreign('crm_customer_id')->references('id')->on('crm_customer')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('call_status_id')->nullable();
            $table->foreign('call_status_id')->references('id')->on('call_status')->onUpdate('cascade')->onDelete('cascade');
            $table->text('message')->nullable();
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
        Schema::drop('sales_chat');
    }
}
