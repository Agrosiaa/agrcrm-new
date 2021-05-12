<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrmCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_customer', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_number_status_id');
            $table->foreign('customer_number_status_id')->references('id')->on('customer_number_status')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('number',15);
            $table->boolean('is_abandoned')->nullable();
            $table->boolean('is_active')->default(true)->nullable();
            $table->string('lead_source')->nullable();
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
        Schema::drop('crm_customer');
    }
}
