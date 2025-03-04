<?php

use Illuminate\Database\Seeder;
use \App\EcommerceModel\SalesHeader;
use \App\EcommerceModel\SalesDetail;
use \App\AutoshipModel\Autoship;
use \App\AutoshipModel\AutoshipDetail;

class AutoshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($x = 1; $x<=10; $x++){

            $faker = \Faker\Factory::create();
            $month = (strlen($x)==1) ? '0'.$x : $x;
            $rec = Autoship::create([
                'user_id' => 2,
                'sales_id' => 1,
                'delivery_date' => '2020-'.$month.'-15',
                'status' => 'PENDING',
                'schedule_type' => 'MONTHLY'
            ]);

            for($p = 1; $p<=3; $p++){
                $salesdetail = \DB::table('ecommerce_sales_details')->where('id',$p)->first();
                $details = AutoshipDetail::create([
                    'autoship_header_id' => $rec->id,
                    'product_id' => '1',
                    'price' => '2',
                    'qty' => '2',
                    'uom' => '2'
                ]);
            }


        }

        for($x = 1; $x<=5; $x++){

            $faker = \Faker\Factory::create();

            $rec = Autoship::create([
                'user_id' => 2,
                'sales_id' => 1,
                'delivery_date' => '202'.$x.'-01-15',
                'status' => 'PENDING',
                'schedule_type' => 'YEARLY'
            ]);

            for($p = 1; $p<=3; $p++){
                $salesdetail = \DB::table('ecommerce_sales_details')->where('id',$p)->first();
                $details = AutoshipDetail::create([
                    'autoship_header_id' => $rec->id,
                    'product_id' => '2',
                    'price' => '2',
                    'qty' => '2',
                    'uom' => '2'
                ]);
            }


        }
    }
}
