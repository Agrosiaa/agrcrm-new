<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class GardenerTagTypeSeeder extends Seeder
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
                'name' => 'Indoor Plant',
                'slug' => 'indoor-plant',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Outdoor Plant',
                'slug' => 'outdoor-plant',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Garden Plant',
                'slug' => 'garden-plant',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Flower',
                'slug' => 'flower',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Vegetable',
                'slug' => 'vegetable',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Fruit',
                'slug' => 'fruit',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Medicinal',
                'slug' => 'medicinal',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Gardening Tool',
                'slug' => 'gardening-tool',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
