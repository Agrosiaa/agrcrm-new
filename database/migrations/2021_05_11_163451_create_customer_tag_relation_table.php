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
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('crm_customer_id');
            $table->foreign('crm_customer_id')->references('id')->on('crm_customer')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('tag_cloud_id');
            $table->foreign('tag_cloud_id')->references('id')->on('tag_cloud')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('tag_type_id');
            $table->boolean('is_deleted')->default(false);
            $table->unsignedInteger('deleted_tag_user')->nullable();
            $table->foreign('deleted_tag_user')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamp('deleted_datetime')->nullable();
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
