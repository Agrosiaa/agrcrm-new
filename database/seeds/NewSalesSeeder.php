<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class NewSalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Sales 5',
                'user_name' => 'AGR0008A',
                'password' => bcrypt('sbsales@07'),
                'is_active' => 1,
                'employ_code' => 6,
                'remember_token' => csrf_token(),
                'role_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
         ]);
    }
}
