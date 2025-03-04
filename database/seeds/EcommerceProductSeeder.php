<?php

use Illuminate\Database\Seeder;

class EcommerceProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\EcommerceProduct::class, 150)->create();
    }
}
