<?php

use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $setting = [
            'id' => 1,
            'api_key' => '',
            'website_name' => 'Lydias',
            'website_favicon' => 'favicon.ico',
            'company_logo' => 'logo.png',
            'company_favicon' => '',
            'company_name' => 'Lydias',
            'company_about' => '',
            'company_address' => '407 West Tower, Philippine Stock Exchange Bldg. Exchange Road Ortigas Center, Pasig City, 1602 Metro Manila',
            'google_analytics' => '',
            'google_map' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14917.830083111654!2d-73.65783255789836!3d45.465301998048886!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4cc917153ba67f8f%3A0xa508f1e92565d250!2s5544+Avenue+Rosedale%2C+C%C3%B4te+Saint-Luc%2C+QC+H4V+2J1%2C+Canada!5e0!3m2!1sen!2sph!4v1564111296278!5m2!1sen!2sph',
            'google_recaptcha_sitekey' => '6Lfgj7cUAAAAAJfCgUcLg4pjlAOddrmRPt86tkQK',
            'google_recaptcha_secret' => '6Lfgj7cUAAAAALOaFTbSFgCXpJldFkG8nFET9eRx',
            'data_privacy_title' => 'Privacy-Policy',
            'data_privacy_popup_content' => 'This website uses cookies to ensure you get the best experience.',
            'data_privacy_content' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
            'mobile_no' => '(639) 912-345-6789',
            'fax_no' => '(632) 8 706-1234',
            'tel_no' => '(632) 8 706-1234',
            'email' => 'support@lydiaslechon.ph',
            'social_media_accounts' => '',
            'copyright' => '',
            'user_id' => '1',

        ];

        DB::table('settings')->insert($setting);
    }
}
