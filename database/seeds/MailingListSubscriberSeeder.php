<?php

use Illuminate\Database\Seeder;

class MailingListSubscriberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\MailingListModel\Subscriber::class, 50)->create();
    }
}
