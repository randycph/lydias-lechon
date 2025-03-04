<?php

namespace database\seeds;
use Illuminate\Database\Seeder;
use DB;

class JoborderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orders = [
            [
               'user_id' => '1', 
               'jo_number' => 'jo0001', 
               'sales_number' => 'sa0001', 
               'sales_detail_id' => '10', 
               'order_source' => 'Pasay',
               'product_id' => '5',
               'product_name' => 'Lechon Baboy',
               'product_size' => 'Medium',
               'product_weight' => '20-30 kls',
               'product_category' => 'Lechon', 
               'customer_name' => 'Juan Dela Cruz', 
               'customer_mobile_number' => '09566725076',
               'customer_tel_number' => '0822226548', 
               'customer_address' => '509 Fedman Suites Salcedo StreetLagaspi Village 1200, Makati City, Metro Manila', 
               'customer_delivery_adress' => '509 Fedman Suites Salcedo StreetLagaspi Village 1200, Makati City, Metro Manila', 
               'delivery_tracking_number' => 'dt0001', 
               'delivery_fee_amount' => '500',
               'delivery_method' => 'door to door',
               'pickup_branch' => '',
               'date_needed' => '2020-03-05 08:00:00',
                'delivery_status' => 'On Processed', 
                'status' => 'Active'
            ],    
            [
               'user_id' => '1', 
               'jo_number' => 'jo0002', 
               'sales_number' => 'sa0002', 
               'sales_detail_id' => '11', 
               'order_source' => 'Pasay',
               'product_id' => '5',
               'product_category' => 'Lechon', 
               'product_name' => 'Lechon Baka',
               'product_size' => 'Large',
               'product_weight' => '50-60 kls', 
               'customer_name' => 'Katrina Christian Panantaon España', 
               'customer_mobile_number' => '09566725076',
               'customer_tel_number' => '0822226548', 
               'customer_address' => '310 San Rafael Street San Miguel 1005, Manila, Metro Manila', 
               'customer_delivery_adress' => 'Pasay', 
               'delivery_tracking_number' => 'dt0002', 
               'delivery_fee_amount' => '500',
               'delivery_method' => 'Store Pickup',
               'pickup_branch' => 'Pasay',
               'date_needed' => '2020-03-04 05:00:00',
                'delivery_status' => 'On Processed', 
                'status' => 'Active'
            ],       
            [
               'user_id' => '1', 
               'jo_number' => 'jo0003', 
               'sales_number' => 'sa0003', 
               'sales_detail_id' => '12', 
               'order_source' => 'Web',
               'product_category' => 'Lechon', 
               'product_id' => '5',
               'product_name' => 'Lechon Baboy',
               'product_size' => 'Small',
               'product_weight' => '10-19 kls', 
               'customer_name' => 'Silverio Kyree Co Rojasz', 
               'customer_mobile_number' => '09566725076',
               'customer_tel_number' => '0822226548', 
               'customer_address' => '2258 Avocado StreetDasmarinas Village 1200, Makati City, Metro Manila', 
               'customer_delivery_adress' => '2258 Avocado StreetDasmarinas Village 1200, Makati City, Metro Manila', 
               'delivery_tracking_number' => 'dt0003', 
               'delivery_fee_amount' => '500',
               'delivery_method' => 'door to door',
               'pickup_branch' => '',
               'date_needed' => '2020-03-04 05:00:00',
                'delivery_status' => 'On Processed', 
                'status' => 'Active'
            ],       

           
        ];

        DB::table('job_orders')->insert($orders);
    }
}
