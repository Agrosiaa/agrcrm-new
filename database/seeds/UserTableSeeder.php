<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UserTableSeeder extends Seeder
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
                'name' => 'Sales Admin',
                'user_name' => 'AGR0006',
                'password' => bcrypt('salesadmin@123'),
                'is_active' => 1,
                'employ_code' => 1,
                'remember_token' => csrf_token(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Sales 1',
                'user_name' => 'AGR0008',
                'password' => bcrypt('sales1@111'),
                'is_active' => 1,
                'employ_code' => 2,
                'remember_token' => csrf_token(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Sales 2',
                'user_name' => 'AGR0009',
                'password' => bcrypt('sales2@222'),
                'is_active' => 1,
                'employ_code' => 3,
                'remember_token' => csrf_token(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Sales 3',
                'user_name' => 'AGR0010',
                'password' => bcrypt('sales3@333'),
                'is_active' => 1,
                'employ_code' => 4,
                'remember_token' => csrf_token(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Sales 4',
                'user_name' => 'AGR0014',
                'password' => bcrypt('sales4@444'),
                'is_active' => 1,
                'employ_code' => 5,
                'remember_token' => csrf_token(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
