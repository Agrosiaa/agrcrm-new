<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class NewTagTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tag_type')->insert([
            [
                'name' => 'Pesticide',
                'slug' => 'pesticide',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Tool',
                'slug' => 'tool',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Seed',
                'slug' => 'seed',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Seed-Pesticide Brand',
                'slug' => 'seed-pesticide-brand',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
