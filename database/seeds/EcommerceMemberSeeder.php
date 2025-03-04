<?php

use Illuminate\Database\Seeder;

class EcommerceMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $entityTypes = ['Individual', 'Partnership', 'Corporation'];
        $ranks = ['Warrior', 'Ranger', 'Elite Ranger', 'Master', 'Grand Master', 'Legend', 'Grand Legend'];
        $securityQuestions = ['What was the house number and street name you lived in as a child?',
            'What were the last four digits of your childhood telephone number?',
            'What primary school did you attend?',
            'In what town or city was your first full time job?',
            'In what town or city did you meet your spouse/partner?',
            'What is the middle name of your oldest child?',
            'What are the last five digits of your driver\'s licence number?',
            'What is your grandmother\'s (on your mother\'s side) maiden name?',
            'What is your spouse or partner\'s mother\'s maiden name?'
        ];

        \App\EcommerceModel\Member::create([
            'user_id' => 2,
            'sponsor_id' => 0,
            'code' => $this->get_random_code(),
            'entity_type' => $entityTypes[array_rand($entityTypes)],

            'government_id_type' => 'TIN',
            'government_id' => '4434 3454 3455 000',

            'first_name' => 'Legande',
            'middle_name' => '',
            'last_name' => 'Member1',
            'birthday' => date('Y-m-d',strtotime('12/30/1996')),

            'email' => 'legande.member1@gmail.com',
            'mobile' => '09748392284',
            'phone' => '044 8 756 4856',
            'work_phone' => '044 8 756 4857',
            'fax' => '83300',

            'address_street' => '0155 General Alejo Road, Bintog',
            'address_city' => 'Plaridel',
            'address_province' => 'Bulacan',
            'address_zip' => '3004',
            'address_country' => 'Philippines',

            'address_delivery_street' => '0155 General Alejo Road, Bintog',
            'address_delivery_city' => 'Plaridel',
            'address_delivery_province' => 'Bulacan',
            'address_delivery_zip' => '3004',
            'address_delivery_country' => 'Philippines',

            'security_question' => 'What primary school did you attend?',
            'security_answer' => 'PBC Sunbeam School',

            'status' => 'active',
            'class' => 'Grand Legend',
        ]);

//        for($x=1001;$x<=1031;$x++) {
//            $firstName = $faker->firstName;
//            $lastName = $faker->lastName;
//
//            $name = $faker->firstName.' '.$faker->lastName;
//            $username = strtolower($faker->firstName.'_'.$faker->lastName);
//            $user = \App\User::create([
//                'name' => $name,
//                'firstname' => $faker->firstName,
//                'lastname' => $faker->lastName,
//                'email' => $faker->unique()->safeEmail,
//                'email_verified_at' => now(),
//                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
//                'is_active' => '1',
//                'user_type' => 'member'
//            ]);
//
//
//            $governmentId = $faker->numberBetween(100000000, 999999999);
//
//            $street = $faker->streetName;
//            $city = $faker->city;
//            $province = $faker->state;
//            $zip = $faker->postcode;
//            $country = $faker->country;
//
//            $mobile = $faker->numberBetween(100000000, 999999999);
//            $phone = $faker->numberBetween(1000000, 9999999);
//            $work_phone = $faker->numberBetween(1000000, 9999999);
//            $fax = $faker->numberBetween(100000, 999999);
//
//            $securityAnswer = $faker->word;
//
//            $sponsor_id = null;
//            if(rand(0,2)) {
//                $ids = \App\EcommerceModel\Member::all()->pluck('id')->toArray();
//                if (count($ids))
//                    $sponsor_id = $ids[array_rand($ids)];
//            }
//
//            \App\EcommerceModel\Member::create([
//                'user_id' => $user->id,
//                'sponsor_id' => $sponsor_id,
//                'code' => $this->get_random_code(),
//                'entity_type' => $entityTypes[array_rand($entityTypes)],
//
//                'government_id_type' => 'TIN',
//                'government_id' => $governmentId,
//                'government_id_photo' => '',
//
//                'first_name' => $firstName,
//                'middle_name' => '',
//                'last_name' => $lastName,
//                'birthday' => date('Y-m-d',strtotime($faker->numberBetween(1, 12).'/'.$faker->numberBetween(1, 31).'/'.$faker->numberBetween(1970, 2000))),
//
//                'email' => $user->email,
//                'mobile' => $mobile,
//                'phone' => $phone,
//                'work_phone' => $work_phone,
//                'fax' => $fax,
//
//                'address_street' => $street,
//                'address_city' => $city,
//                'address_province' => $province,
//                'address_zip' => $zip,
//                'address_country' => $country,
//
//                'address_delivery_street' => $street,
//                'address_delivery_city' => $city,
//                'address_delivery_province' => $province,
//                'address_delivery_zip' => $zip,
//                'address_delivery_country' => $country,
//
//                'security_question' => $securityQuestions[array_rand($securityQuestions)],
//                'security_answer' => $securityAnswer,
//
//                'status' => 'active',
//                'class' => $ranks[array_rand($ranks)],
//            ]);
//        }
    }

    public function get_random_code($length = 6)
    {
        $token = "";
        $codeAlphabet= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet); // edited

        $member = \App\EcommerceModel\Member::where('code', $token)->first();

        while($token == "" || $member) {
            $token = "";
            for ($i = 0; $i < $length; $i++) {
                $token .= $codeAlphabet[random_int(0, $max-1)];
            }
            $member = \App\EcommerceModel\Member::where('code', $token)->first();
        }

        return $token;
    }
}
