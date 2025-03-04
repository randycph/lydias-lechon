<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $users = [
            [
                'username' =>  'admin',
                'name' => 'admin istrator',
                'firstname' => 'admin',
                'lastname' => 'istrator',
                'email' => 'wsiprod.demo@gmail.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'avatar' => \URL::to('/').'/images/user.png',
                'remember_token' => Str::random(10),
                'role_id' => 1,
                'user_type' => 'cms',
                'is_active' => 1,
                'user_id' => 1,
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26',
                'address_street' => 'street',
                'address_municipality' => 'municipality',
                'address_city' => 'city',
                'address_region' => 'region',
            ],
            [
                'username' =>  'lydias_admin',
                'name' => 'lydias admin',
                'firstname' => 'lydias',
                'lastname' => 'admin',
                'email' => 'lydias_admin@gmail.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'avatar' => \URL::to('/').'/images/user.png',
                'remember_token' => Str::random(10),
                'role_id' => 1,
                'user_type' => 'cms',
                'is_active' => 1,
                'user_id' => 1,
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26',
                'address_street' => 'street',
                'address_municipality' => 'municipality',
                'address_city' => 'city',
                'address_region' => 'region',
            ],
            [
                'username' =>  'lydias_steven',
                'name' => 'steven roth',
                'firstname' => 'steven',
                'lastname' => 'roth',
                'email' => 'lydias_steven@gmail.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'avatar' => \URL::to('/').'/images/user.png',
                'remember_token' => Str::random(10),
                'role_id' => 2, //branch manager
                'user_type' => 'cms',
                'is_active' => 1,
                'user_id' => 1,
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26',
                'address_street' => 'street',
                'address_municipality' => 'municipality',
                'address_city' => 'city',
                'address_region' => 'region',
            ],
            [
                'username' =>  'lydias_ana',
                'name' => 'ana foster',
                'firstname' => 'ana',
                'lastname' => 'foster',
                'email' => 'lydias_ana@gmail.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'avatar' => \URL::to('/').'/images/user.png',
                'remember_token' => Str::random(10),
                'role_id' => 3, //forecaster
                'user_type' => 'cms',
                'is_active' => 1,
                'user_id' => 1,
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26',
                'address_street' => 'street',
                'address_municipality' => 'municipality',
                'address_city' => 'city',
                'address_region' => 'region',
            ],
            [
                'username' =>  'lydias_lynn',
                'name' => 'lynn scherman',
                'firstname' => 'lynn',
                'lastname' => 'scherman',
                'email' => 'lydias_lynn@gmail.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'avatar' => \URL::to('/').'/images/user.png',
                'remember_token' => Str::random(10),
                'role_id' => 4, //staff
                'user_type' => 'cms',
                'is_active' => 1,
                'user_id' => 1,
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26',
                'address_street' => 'street',
                'address_municipality' => 'municipality',
                'address_city' => 'city',
                'address_region' => 'region',
            ],
            [
                'username' =>  'lydias_hina',
                'name' => 'hina bach',
                'firstname' => 'hina',
                'lastname' => 'bach',
                'email' => 'lydias_hina@gmail.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'avatar' => \URL::to('/').'/images/user.png',
                'remember_token' => Str::random(10),
                'role_id' => 5, //dispatcher
                'user_type' => 'cms',
                'is_active' => 1,
                'user_id' => 1,
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26',
                'address_street' => 'street',
                'address_municipality' => 'municipality',
                'address_city' => 'city',
                'address_region' => 'region',
            ],
            [
                'username' => '',
                'name' => 'Fatema Mcdowell',
                'firstname' => 'Fatema',
                'lastname' => 'Mcdowell',
                'email' => 'fatema@gmail.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'avatar' => \URL::to('/').'/images/user.png',
                'remember_token' => Str::random(10),
                'role_id' => '',
                'user_type' => 'customer',
                'is_active' => 1,
                'user_id' => 1,
                'address_street' => 'street',
                'address_municipality' => 'municipality',
                'address_city' => 'city',
                'address_region' => 'region',
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26'
            ],
            [
                'username' => '',
                'name' => 'Serena Whelan',
                'firstname' => 'Serena',
                'lastname' => 'Whelan',
                'email' => 'serena@gmail.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'avatar' => \URL::to('/').'/images/user.png',
                'remember_token' => Str::random(10),
                'role_id' => '',
                'user_type' => 'customer',
                'is_active' => 1,
                'user_id' => 1,
                'address_street' => 'street',
                'address_municipality' => 'municipality',
                'address_city' => 'city',
                'address_region' => 'region',
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26'
            ],
            [
                'username' => '',
                'name' => 'Fabian Aldred',
                'firstname' => 'Fabian',
                'lastname' => 'Aldred',
                'email' => 'fabian@gmail.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'avatar' => \URL::to('/').'/images/user.png',
                'remember_token' => Str::random(10),
                'role_id' => '',
                'user_type' => 'customer',
                'is_active' => 1,
                'user_id' => 1,
                'address_street' => 'street',
                'address_municipality' => 'municipality',
                'address_city' => 'city',
                'address_region' => 'region',
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26'
            ]
        ];

        DB::table('users')->insert($users);
    }
}
