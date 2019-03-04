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
                'email' => 'salesadmin@gmail.com',
                'password' => bcrypt('salesadmin@123'),
                'is_active' => 1,
                'employ_code' => 6,
                'remember_token' => csrf_token(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Sales 1',
                'email' => 'sales1@gmail.com',
                'password' => bcrypt('sales1@111'),
                'is_active' => 1,
                'employ_code' => 8,
                'remember_token' => csrf_token(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Sales 2',
                'email' => 'sales2@gmail.com',
                'password' => bcrypt('sales2@222'),
                'is_active' => 1,
                'employ_code' => 9,
                'remember_token' => csrf_token(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Sales 3',
                'email' => 'salesadmin@gmail.com',
                'password' => bcrypt('sales3@333'),
                'is_active' => 1,
                'employ_code' => 10,
                'remember_token' => csrf_token(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Sales 4',
                'email' => 'salesadmin@gmail.com',
                'password' => bcrypt('sales4@444'),
                'is_active' => 1,
                'employ_code' => 14,
                'remember_token' => csrf_token(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
