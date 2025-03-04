<?php

use Illuminate\Database\Seeder;

class ProductVariationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $variations = [
            // Black Realme
            [
                'product_id' => '1',
//                'color' => 'Black',
                'size' => '64GB 6GB'
            ],
            [
                'product_id' => '1',
//                'color' => 'Black',
                'size' => '128GB'
            ],
            [
                'product_id' => '1',
//                'color' => 'Black',
                'size' => '64GB 4GB'
            ],
            // Blue Realme
            [
                'product_id' => '1',
//                'color' => 'Blue',
                'size' => '64GB 6GB'
            ],
            [
                'product_id' => '1',
//                'color' => 'Blue',
                'size' => '128GB'
            ],
            [
                'product_id' => '1',
//                'color' => 'Blue',
                'size' => '64GB 4GB'
            ],
            //White Realme
            [
                'product_id' => '1',
//                'color' => 'White',
                'size' => '64GB 6GB'
            ],
            [
                'product_id' => '1',
//                'color' => 'White',
                'size' => '128GB'
            ],
            [
                'product_id' => '1',
//                'color' => 'White',
                'size' => '64GB 4GB'
            ],

            // Black Shoes
            [
                'product_id' => '2',
//                'color' => 'Black',
                'size' => 'Small'
            ],
            [
                'product_id' => '2',
//                'color' => 'Black',
                'size' => 'Medium'
            ],
            [
                'product_id' => '2',
//                'color' => 'Black',
                'size' => 'Large'
            ],
            // Blue Shoes
            [
                'product_id' => '2',
//                'color' => 'Blue',
                'size' => 'Small'
            ],
            [
                'product_id' => '2',
//                'color' => 'Blue',
                'size' => 'Medium'
            ],
            [
                'product_id' => '2',
//                'color' => 'Blue',
                'size' => 'Large'
            ],
            //White Shoes
            [
                'product_id' => '2',
//                'color' => 'White',
                'size' => 'Small'
            ],
            [
                'product_id' => '2',
//                'color' => 'White',
                'size' => 'Medium'
            ],
            [
                'product_id' => '2',
//                'color' => 'White',
                'size' => 'Large'
            ],

            // Black Watch
            [
                'product_id' => '3',
//                'color' => 'Black',
                'size' => 'Small'
            ],
            [
                'product_id' => '3',
//                'color' => 'Black',
                'size' => 'Medium'
            ],
            [
                'product_id' => '3',
//                'color' => 'Black',
                'size' => 'Large'
            ],
            // Blue Watch
            [
                'product_id' => '3',
//                'color' => 'Blue',
                'size' => 'Small'
            ],
            [
                'product_id' => '3',
//                'color' => 'Blue',
                'size' => 'Medium'
            ],
            [
                'product_id' => '3',
//                'color' => 'Blue',
                'size' => 'Large'
            ],
            //White Watch
            [
                'product_id' => '3',
//                'color' => 'White',
                'size' => 'Small'
            ],
            [
                'product_id' => '3',
//                'color' => 'White',
                'size' => 'Medium'
            ],
            [
                'product_id' => '3',
//                'color' => 'White',
                'size' => 'Large'
            ],



        ];

        DB::table('products_variations')->insert($variations);
    }
}
