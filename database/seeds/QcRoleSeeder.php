<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;


class QcRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'name' => 'QC Admin',
                'slug' => 'qc_admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
