<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerTagRelationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_tag_relation', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('crm_customer_id');
            $table->foreign('crm_customer_id')->references('id')->on('crm_customer')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('tag_cloud_id');
            $table->foreign('tag_cloud_id')->references('id')->on('tag_cloud')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::drop('customer_tag_relation');
    }
}
