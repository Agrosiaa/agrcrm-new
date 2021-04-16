<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CallBackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('call_back')->insert([
            [
                'name' => 'Call Back 1',
                'slug' => 'call-back-1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Call Back 2',
                'slug' => 'call-back-2',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Call Back 3',
                'slug' => 'call-back-3',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
