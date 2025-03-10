<?php

use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Menu::insert([
            'name' => 'Menu 1',
            'is_active' => 1,
            'pages_json' => '[]',
            'created_at' => '2019-10-06 20:31:26',
            'updated_at' => '2019-10-06 20:31:26'
        ]);
    }
}
