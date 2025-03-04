<?php

use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $categories = [
            [
                'parent_id' => '0',
                'name' => 'Best Sellers',
                'slug' => 'best-sellers',
                'description' => '',
                'status' => 'PUBLISHED',
                'created_by' => 1
            ],
            [
                'parent_id' => '0',
                'name' => 'Lechon Quick Meals',
                'slug' => 'lechon-quick-meals',
                'description' => '',
                'status' => 'PUBLISHED',
                'created_by' => 1
            ],
            [
                'parent_id' => '0',
                'name' => 'Gulay Atbp',
                'slug' => 'gulay-atbp',
                'description' => '',
                'status' => 'PUBLISHED',
                'created_by' => 1
            ],
            [
                'parent_id' => '0',
                'name' => 'Lechon Espesyal',
                'slug' => 'lechon-espesyal',
                'description' => '',
                'status' => 'PUBLISHED',
                'created_by' => 1
            ],
            [
                'parent_id' => '0',
                'name' => 'Meaty Espesyal',
                'slug' => 'meaty-espesyal',
                'description' => '',
                'status' => 'PUBLISHED',
                'created_by' => 1
            ],
            [
                'parent_id' => '0',
                'name' => 'Meryenda',
                'slug' => 'meryenda',
                'description' => '',
                'status' => 'PUBLISHED',
                'created_by' => 1
            ],
            [
                'parent_id' => '0',
                'name' => 'Chicharon Bituka',
                'slug' => 'chicharon-bituka',
                'description' => '',
                'status' => 'PRIVATE',
                'created_by' => 1
            ],
            [
                'parent_id' => '0',
                'name' => 'Pampagana',
                'slug' => 'pampagana',
                'description' => '',
                'status' => 'PUBLISHED',
                'created_by' => 1
            ],
            [
                'parent_id' => '0',
                'name' => 'Panghimagas',
                'slug' => 'panghimagas',
                'description' => '',
                'status' => 'PUBLISHED',
                'created_by' => 1
            ],
            [
                'parent_id' => '0',
                'name' => 'Sinabawan',
                'slug' => 'sinabawan',
                'description' => '',
                'status' => 'PUBLISHED',
                'created_by' => 1
            ],
            [
                'parent_id' => '0',
                'name' => 'Yamang Dagat',
                'slug' => 'yamang-dagat',
                'description' => '',
                'status' => 'PUBLISHED',
                'created_by' => 1
            ]
        ];

        DB::table('product_categories')->insert($categories);
    }
}
