<?php

use Illuminate\Database\Seeder;

class ProductPhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $products_photos = [
            [
                'product_id' => '1',
                'name' => '',
                'description' => '',
                'path' => '1/Dinuguan.png',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '2',
                'name' => '',
                'description' => '',
                'path' => '2/Chopsuey.png',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '3',
                'name' => '',
                'description' => '',
                'path' => '3/Laing-con-Lechon.png',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '4',
                'name' => '',
                'description' => '',
                'path' => '4/LQM-Chopsuey.png',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '5',
                'name' => '',
                'description' => '',
                'path' => '5/Fresh-Lumpia.png',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '6',
                'name' => '',
                'description' => '',
                'path' => '6/Kare-kare-Classic.png',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '7',
                'name' => '',
                'description' => '',
                'path' => '7/Lechon-Paksiw.png',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '8',
                'name' => '',
                'description' => '',
                'path' => '8/Lechon-Sinigang.png',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '9',
                'name' => '',
                'description' => '',
                'path' => '9/Lechon-Sisig.png',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '10',
                'name' => '',
                'description' => '',
                'path' => '10/Pancit-canton-con-Lechon.png',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '11',
                'name' => '',
                'description' => '',
                'path' => '11/Pinakbet.png',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '12',
                'name' => '',
                'description' => '',
                'path' => '12/Sinigang-na-Ulo-ng-Salmon.png',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '13',
                'name' => '',
                'description' => '',
                'path' => '13/LQM-Dinuguan.png',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '14',
                'name' => '',
                'description' => '',
                'path' => '14/LQM-Kare-kare.png',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '15',
                'name' => '',
                'description' => '',
                'path' => '15/LQM-Pinakbet.png',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '16',
                'name' => '',
                'description' => '',
                'path' => '16/Kare-kare-con-Lechon.png',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '17',
                'name' => '',
                'description' => '',
                'path' => '17/Sizzling-Bopis.png',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '18',
                'name' => '',
                'description' => '',
                'path' => '18/Dinuguan-with-Puto.png',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '19',
                'name' => '',
                'description' => '',
                'path' => '19/Lumpiang-Ubod-Prito.png',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '20',
                'name' => '',
                'description' => '',
                'path' => '20/Pancit-Mixed.png',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '21',
                'name' => '',
                'description' => '',
                'path' => '21/Chicharon-Bituka.png',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '22',
                'name' => '',
                'description' => '',
                'path' => '22/Garlic-Chicharon-Bulaklak.png',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '23',
                'name' => '',
                'description' => '',
                'path' => '23/Manggat-Bagoong.png',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '24',
                'name' => '',
                'description' => '',
                'path' => '24/Buko-Pandan.png',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '25',
                'name' => '',
                'description' => '',
                'path' => '25/Leche-Flan-Small.png',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '26',
                'name' => '',
                'description' => '',
                'path' => '26/Mango-Sago.png',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '27',
                'name' => '',
                'description' => '',
                'path' => '27/Beef-Papaitan.png',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '28',
                'name' => '',
                'description' => '',
                'path' => '28/Bangus-Ala-Pobre.png',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '29',
                'name' => '',
                'description' => '',
                'path' => '29/Rellenong-Alimango.png',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '30',
                'name' => '',
                'description' => '',
                'path' => '30/1-2-Lechon-2.png',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '30',
                'name' => '',
                'description' => '',
                'path' => '30/1-2-Lechon-3.png',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '30',
                'name' => '',
                'description' => '',
                'path' => '30/1-2-Lechon.png',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '31',
                'name' => '',
                'description' => '',
                'path' => '31/1-4-Kilo-Lechon.png',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '32',
                'name' => '',
                'description' => '',
                'path' => '32/lechon.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '33',
                'name' => '',
                'description' => '',
                'path' => '33/lechon.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '34',
                'name' => '',
                'description' => '',
                'path' => '34/lechon.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '0',
                'created_by' => 1
            ],
            [
                'product_id' => '35',
                'name' => '',
                'description' => '',
                'path' => '35/lechon.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '36',
                'name' => '',
                'description' => '',
                'path' => '36/lechon.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '37',
                'name' => '',
                'description' => '',
                'path' => '37/lechon.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '38',
                'name' => '',
                'description' => '',
                'path' => '38/lechon.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '39',
                'name' => '',
                'description' => '',
                'path' => '39/lechon.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '40',
                'name' => '',
                'description' => '',
                'path' => '40/lechon.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '41',
                'name' => '',
                'description' => '',
                'path' => '41/lechon.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '42',
                'name' => '',
                'description' => '',
                'path' => '42/lechonbaka.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '43',
                'name' => '',
                'description' => '',
                'path' => '43/liab1.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '44',
                'name' => '',
                'description' => '',
                'path' => '44/liab2.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '45',
                'name' => '',
                'description' => '',
                'path' => '45/liab4 (2).jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ]

        ];

        DB::table('product_photos')->insert($products_photos);
    }
}
