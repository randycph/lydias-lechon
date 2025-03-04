<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Role::insert([
            [
                'name' => 'Admin',
                'description' => 'Administrator of the system',
                'created_by'    => 1,
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26'
            ],
           [
               'name' => 'Branch',
               'description' => 'Able to monitor and analyze branch perfomance data via store portal (sales transaction, daily report)',
               'created_by'    => 1,
               'created_at' => '2019-10-06 20:31:26',
               'updated_at' => '2019-10-06 20:31:26'
           ],
           [
               'name' => 'Forecaster',
               'description' => 'Able to allocate/assign job orders to production branches',
               'created_by'    => 1,
               'created_at' => '2019-10-06 20:31:26',
               'updated_at' => '2019-10-06 20:31:26'
           ],
           [
                'name' => 'Staff',
                'description' => 'Able to input sales transactions from stores',
                'created_by'    => 1,
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26'
            ],
            [
                 'name' => 'Dispatcher',
                 'description' => 'Able to assign delivery to store or door-to-door',
                 'created_by'    => 1,
                 'created_at' => '2019-10-06 20:31:26',
                 'updated_at' => '2019-10-06 20:31:26'
            ], 
            [
                'name' => 'Customer',
                'description' => 'Able to order online (website) or in store (store portal)',
                'created_by'    => 1,
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26'
            ],
        ]);
    }
}
