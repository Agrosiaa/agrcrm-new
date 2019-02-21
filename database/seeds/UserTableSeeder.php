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
                'name' => 'Nishank Rathod',
                'email' => 'nishank@gmail.com',
                'password' => bcrypt('admin'),
                'is_active' => 1,
                'employ_code' => 1,
                'remember_token' => csrf_token(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
