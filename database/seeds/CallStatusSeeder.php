<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CallStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('call_status')->insert([
            [
                'name' => 'Connected',
                'slug' => 'connected',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Invalid',
                'slug' => 'invalid',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Out of Coverage Area',
                'slug' => 'out-of-coverage-area',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Rejected / Busy',
                'slug' => 'rejected-busy',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Ringing',
                'slug' => 'ringing',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Mobile Switched Off',
                'slug' => 'mobile-switched-off',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
