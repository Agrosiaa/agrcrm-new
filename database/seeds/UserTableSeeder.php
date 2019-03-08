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
                'role_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Sales 1',
                'user_name' => 'AGR0008',
                'password' => bcrypt('nsales@123'),
                'is_active' => 1,
                'employ_code' => 2,
                'remember_token' => csrf_token(),
                'role_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Sales 2',
                'user_name' => 'AGR0009',
                'password' => bcrypt('msales@987'),
                'is_active' => 1,
                'employ_code' => 3,
                'remember_token' => csrf_token(),
                'role_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Sales 3',
                'user_name' => 'AGR0010',
                'password' => bcrypt('asales@789'),
                'is_active' => 1,
                'employ_code' => 4,
                'remember_token' => csrf_token(),
                'role_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Sales 4',
                'user_name' => 'AGR0014',
                'password' => bcrypt('vsales@635'),
                'is_active' => 1,
                'employ_code' => 5,
                'remember_token' => csrf_token(),
                'role_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
