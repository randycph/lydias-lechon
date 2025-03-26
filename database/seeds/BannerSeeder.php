<?php

use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Banner::insert([
            [
                'album_id' => 1,
                'image_path' => asset('theme/'.config('app.frontend_template').'/images/banners/banner1.jpg'),
                'title' => 'EVERYDAY LECHON HAPPINESS',
                'description' => 'HOME OF THE PHILIPPINES BEST TASTING ORIGINAL BONELESS LECHON WITH PAELLA.',
                'alt' => '',
                'button_text' => '',
                'url' => '',
                'order' => 1,
                'user_id' => 1,
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26'
            ],
            [
                'album_id' => 2,
                'image_path' => asset('theme/'.config('app.frontend_template').'/images/banners/sub/subimage1.jpg'),
                'title' => null,
                'description' => null,
                'alt' => null,
                'button_text' => null,
                'url' => null,
                'order' => 1,
                'user_id' => 1,
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26'
            ],
            [
                'album_id' => 2,
                'image_path' => asset('theme/'.config('app.frontend_template').'/images/banners/sub/subimage2.jpg'),
                'title' => null,
                'description' => null,
                'alt' => null,
                'button_text' => null,
                'url' => null,
                'order' => 2,
                'user_id' => 1,
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26'
            ],[
                'album_id' => 1,
                'image_path' => asset('theme/'.config('app.frontend_template').'/images/banners/banner1.jpg'),
                'title' => 'EVERYDAY LECHON HAPPINESS',
                'description' => 'HOME OF THE PHILIPPINES BEST TASTING ORIGINAL BONELESS LECHON WITH PAELLA.',
                'alt' => '',
                'button_text' => '',
                'url' => '',
                'order' => 1,
                'user_id' => 1,
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26'
            ]
        ]);
    }
}
