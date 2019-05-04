<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCallStatusIdToSalesChatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_chat', function (Blueprint $table) {
            $table->unsignedInteger('call_status_id')->nullable();
            $table->foreign('call_status_id')->references('id')->on('call_status')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_chat', function (Blueprint $table) {
            $table->dropForeign('sales_chat_call_status_id_foreign');
            $table->dropColumn('call_status_id');
        });
    }
}
