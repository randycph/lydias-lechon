<?php

use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $services = [
            [
                'name' => 'Service 1',
                'status' => 'PUBLISHED',
                'created_by' => 1
            ],
            [
                'name' => 'Service 2',
                'status' => 'PUBLISHED',
                'created_by' => 1
            ],
            [
                'name' => 'Service 3',
                'status' => 'PUBLISHED',
                'created_by' => 1
            ],
            [
                'name' => 'Service 4',
                'status' => 'PUBLISHED',
                'created_by' => 1
            ]
        ];

        DB::table('service_types')->insert($services);
    }
}
