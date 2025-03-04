<?php

use Illuminate\Database\Seeder;

class ProductTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $tags = [
            [
                'product_id' => '',
                'tag' => '',
                'created_by' => 1
            ]
        ];

        DB::table('product_tags')->insert($tags);
    }
}
