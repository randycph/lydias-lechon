<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SettingSeeder::class);
        $this->call(AlbumSeeder::class);
        $this->call(BannerSeeder::class);
        $this->call(PageSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(MenusHasPagesSeeder::class);
        $this->call(OptionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(ArticleSeeder::class);
//        $this->call(PermissionSeeder::class);
//        $this->call(RolepermissionSeeder::class);
        $this->users();
        $this->call(EcommerceMemberSeeder::class);
        $this->call(EcommercePageSeeder::class);
        $this->call(ProductCategorySeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(ProductPhotoSeeder::class);
        $this->call(ProductVariationSeeder::class);
        $this->call(BranchSeeder::class);
        $this->call(DeliveryrateSeeder::class);

        // $this->call(EcommerceSalesHeaderSeeder::class);
        // $this->call(EcommerceProductReviewSeeder::class);

    }

    public function users()
    {
        $users = [
            [
                'name' => 'Admin',
                'username' => 'admin',
                'firstname' => 'admin',
                'lastname' => 'istrator',
                'email' => 'wsiprod.demo@gmail.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
                'role_id' => 1,
                'is_active' => 1,
                'user_type' => 'cms',
                'user_id' => 1,
//                'address_street' => 'street',
//                'address_municipality' => 'municipality',
//                'address_city' => 'city',
//                'address_region' => 'region',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'name' => 'Lydias Lechon',
                'username' => 'lydias_admin',
                'firstname' => 'Lydias',
                'lastname' => 'Lechon',
                'email' => 'tats0608@gmail.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
                'role_id' => 1,
                'is_active' => 1,
                'user_type' => 'cms',
                'user_id' => 1,
//                'address_street' => 'street',
//                'address_municipality' => 'municipality',
//                'address_city' => 'city',
//                'address_region' => 'region',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'name' => 'Lydias Lechon',
                'username' => 'admin_lydias',
                'firstname' => 'Lydias',
                'lastname' => 'Lechon',
                'email' => 'mmcs08@gmail.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
                'role_id' => 1,
                'is_active' => 1,
                'user_type' => 'cms',
                'user_id' => 1,
//                'address_street' => 'street',
//                'address_municipality' => 'municipality',
//                'address_city' => 'city',
//                'address_region' => 'region',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'name' => 'Ericka Ericka',
                'username' => 'lydias_ericka',
                'firstname' => 'Ericka',
                'lastname' => 'Ericka',
                'email' => 'eq.lydiaslechon@gmail.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
                'role_id' => 1,
                'is_active' => 1,
                'user_type' => 'cms',
                'user_id' => 1,
//                'address_street' => 'street',
//                'address_municipality' => 'municipality',
//                'address_city' => 'city',
//                'address_region' => 'region',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'name' => 'Zennie Cu',
                'username' => 'lydias_zennie',
                'firstname' => 'Zennie',
                'lastname' => 'Cu',
                'email' => 'infos@lydias-lechon.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
                'role_id' => 1,
                'is_active' => 1,
                'user_type' => 'cms',
                'user_id' => 1,
//                'address_street' => 'street',
//                'address_municipality' => 'municipality',
//                'address_city' => 'city',
//                'address_region' => 'region',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]
        ];

        DB::table('users')->insert($users);
    }
}
