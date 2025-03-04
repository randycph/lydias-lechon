<?php

use Illuminate\Database\Seeder;

class EcommercePromoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\EcommercePromo::class, 150)->create();
    }
}
