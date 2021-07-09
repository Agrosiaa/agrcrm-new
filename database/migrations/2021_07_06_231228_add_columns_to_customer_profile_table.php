<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToCustomerProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_profile', function (Blueprint $table) {
            $table->string('gardening_type');
            $table->string('plant_used');
            $table->string('plant_seed_purchase_from');
            $table->string('plant_fertilizer');
            $table->string('plant_watering');
            $table->string('business_job');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_profile', function (Blueprint $table) {
            $table->dropColumn('gardening_type');
            $table->dropColumn('plant_used');
            $table->dropColumn('plant_seed_purchase_from');
            $table->dropColumn('plant_fertilizer');
            $table->dropColumn('plant_watering');
            $table->dropColumn('business_job');
        });
    }
}
