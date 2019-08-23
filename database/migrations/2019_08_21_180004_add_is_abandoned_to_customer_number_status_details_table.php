<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsAbandonedToCustomerNumberStatusDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_number_status_details', function (Blueprint $table) {
            $table->boolean('is_abandoned')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_number_status_details', function (Blueprint $table) {
            $table->dropColumn('is_abandoned');
        });
    }
}
