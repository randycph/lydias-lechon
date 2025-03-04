<?php

use Illuminate\Database\Seeder;

class DeliveryrateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rates = [
            [
                'region' => 'ARMM',
                'province' => 'Basilan',
                'municipality' => 'Isabela',
                'brgy' => 'Brgy. Dona Ramona T. Alona',
                'zip' => '7300',
                'sla' => '10',
                'rate' => '385.00',
                'remarks' => 'Other Barangays not served',
                'user_id' => '1'
            ],
            [
                'region' => 'ARMM',
                'province' => 'Basilan',
                'municipality' => 'Isabela',
                'brgy' => 'Brgy. Poblacion',
                'zip' => '7300',
                'sla' => '10',
                'rate' => '385.00',
                'remarks' => 'Other Barangays not served',
                'user_id' => '1'
            ],
            [
                'region' => 'NCR - National Capital Region',
                'province' => 'Metro Manila',
                'municipality' => 'Caloocan City (North)',
                'brgy' => 'Amparo Subd',
                'zip' => '1425',
                'sla' => '1 to 2',
                'rate' => '30.00',
                'remarks' => '',
                'user_id' => '1'
            ],
            [
                'region' => 'NCR - National Capital Region',
                'province' => 'Metro Manila',
                'municipality' => 'Caloocan City (South)',
                'brgy' => 'Baesa',
                'zip' => '1401',
                'sla' => '1 to 2',
                'rate' => '20.00',
                'remarks' => '',
                'user_id' => '1'
            ],
            [
                'region' => 'NCR - National Capital Region',
                'province' => 'Metro Manila',
                'municipality' => 'Las Pinas',
                'brgy' => 'Remarville Subd',
                'zip' => '1741',
                'sla' => '1 to 2',
                'rate' => '10.00',
                'remarks' => '',
                'user_id' => '1'
            ],

        ];

        DB::table('ecommerce_delivery_rate')->insert($rates);
    }
}
