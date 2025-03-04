<?php

use Illuminate\Database\Seeder;

class EcommercePageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = [
            [
                'parent_page_id' => 0,
                'album_id' => null,
                'slug' => 'cart',
                'name' => 'Cart',
                'label' => 'Cart',
                'contents' => '',
                'status' => 'PUBLISHED',
                'page_type' => 'uneditable',
                'image_url' => null,
                'meta_title' => null,
                'meta_keyword' => null,
                'meta_description' => null,
                'user_id' => 1,
                'template' => 'cart',
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26'
            ],
            [
                'parent_page_id' => 0,
                'album_id' => null,
                'slug' => 'products-list',
                'name' => 'Products',
                'label' => 'Products',
                'contents' => '',
                'status' => 'PUBLISHED',
                'page_type' => 'uneditable',
                'image_url' => null,
                'meta_title' => null,
                'meta_keyword' => null,
                'meta_description' => null,
                'user_id' => 1,
                'template' => 'product-list',
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26'
            ],
            [
                'parent_page_id' => 0,
                'album_id' => null,
                'slug' => 'product1',
                'name' => 'Product 1',
                'label' => 'Product 1',
                'contents' => '',
                'status' => 'PUBLISHED',
                'page_type' => 'standard',
                'image_url' => null,
                'meta_title' => null,
                'meta_keyword' => null,
                'meta_description' => null,
                'user_id' => 1,
                'template' => 'product',
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26'
            ],
        ];

        DB::table('pages')->insert($pages);
    }
}
