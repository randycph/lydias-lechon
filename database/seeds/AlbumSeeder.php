<?php

use Illuminate\Database\Seeder;

class AlbumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Album::insert([
            [
                'name' => 'Home Banner',
                'transition_in' => 1,
                'transition_out' => 2,
                'transition' => 6,
                'type' => 'main_banner',
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26'
            ],
            [
                'name' => 'Sub Banner',
                'transition_in' => 1,
                'transition_out' => 2,
                'transition' => 6,
                'type' => 'sub_banner',
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26'
            ]
        ]);
    }
}
