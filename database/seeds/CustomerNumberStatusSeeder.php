<?php

use Illuminate\Database\Seeder;
use \Carbon\Carbon;

class CustomerNumberStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('customer_number_status')->insert([
            [
                'name' => 'New',
                'slug' => 'new',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Call Back',
                'slug' => 'call-back',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Complete',
                'slug' => 'complete',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Failed',
                'slug' => 'failed',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
