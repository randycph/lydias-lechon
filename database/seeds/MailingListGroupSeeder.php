<?php

use Illuminate\Database\Seeder;

class MailingListGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\MailingListModel\Group::class, 15)->create();
    }
}
