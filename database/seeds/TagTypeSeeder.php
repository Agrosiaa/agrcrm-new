<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TagTypeSeeder extends Seeder
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
                'name' => 'Product',
                'slug' => 'product',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Category',
                'slug' => 'category',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Crop',
                'slug' => 'crop',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'State',
                'slug' => 'state',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'District',
                'slug' => 'district',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'City',
                'slug' => 'city',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Pincode',
                'slug' => 'pincode',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
            'name' => 'Order',
            'slug' => 'Order',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Order Return',
                'slug' => 'order-return',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
