<?php

use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\EcommerceModel\Branch::insert([
            [
                'name' => 'Q.C. Hotline',
                'code' => '000',
                'address' => 'Quezon City',
                'contact_nos' => '632208939201221',
                'contact_person' => '-',
                'email_address' => '-',
                'user_id' => '1',

            ],
            [
                'name' => 'Baclaran Hotline',
                'code' => '000',
                'address' => 'Baclaran',
                'contact_nos' => '+632208851202987',
                'contact_person' => '-',
                'email_address' => '-',
                'user_id' => '1',

            ],
            [
                'name' => 'Roces Q.C.',
                'code' => '000',
                'address' => 'Roces Quezon City',
                'contact_nos' => '+632208376209016',
                'contact_person' => '-',
                'email_address' => '-',
                'user_id' => '1',

            ],
            [
                'name' => 'Timog Q.C.',
                'code' => '000',
                'address' => 'Timog Quezon City',
                'contact_nos' => '+632208921201221',
                'contact_person' => '-',
                'email_address' => '-',
                'user_id' => '1',

            ],
            [
                'name' => 'Commonwealth Q.C',
                'code' => '000',
                'address' => 'Commonwealth Quezon City',
                'contact_nos' => '+632208935205095',
                'contact_person' => '-',
                'email_address' => '-',
                'user_id' => '1',

            ],
            [
                'name' => 'Marcos Hi-way',
                'code' => '000',
                'address' => 'Marcos Hi-way',
                'contact_nos' => '+632208682208927',
                'contact_person' => '-',
                'email_address' => '-',
                'user_id' => '1',

            ],
            [
                'name' => 'C5 Ugong Pasig',
                'code' => '000',
                'address' => 'C5 Ugong Pasig',
                'contact_nos' => '+632208671209023',
                'contact_person' => '-',
                'email_address' => '-',
                'user_id' => '1',

            ],
            [
                'name' => 'BF Homes',
                'code' => '000',
                'address' => 'BF Homes',
                'contact_nos' => '+632208861200608',
                'contact_person' => '-',
                'email_address' => '-',
                'user_id' => '1',

            ],
            [
                'name' => 'Carry and Carry',
                'code' => '000',
                'address' => 'Carry and Carry',
                'contact_nos' => '+632208475207558',
                'contact_person' => '-',
                'email_address' => '-',
                'user_id' => '1',

            ],
            [
                'name' => 'MDH UN Avenue',
                'code' => '000',
                'address' => 'MDH UN Avenue',
                'contact_nos' => '+632208536201639',
                'contact_person' => '-',
                'email_address' => '-',
                'user_id' => '1',

            ],
            [
                'name' => 'Globe: +63917 538 0304',
                'code' => '000',
                'address' => '-',
                'contact_nos' => '+639175380304',
                'contact_person' => '-',
                'email_address' => '-',
                'user_id' => '1',

            ],
            [
                'name' => 'Globe: +63917 820 2989',
                'code' => '000',
                'address' => '-',
                'contact_nos' => '+639178202989',
                'contact_person' => '-',
                'email_address' => '-',
                'user_id' => '1',

            ],
            [
                'name' => 'Smart: +6918 967 5213',
                'code' => '000',
                'address' => '-',
                'contact_nos' => '+69189675213',
                'contact_person' => '-',
                'email_address' => '-',
                'user_id' => '1',

            ]
        ]);
    }
}
