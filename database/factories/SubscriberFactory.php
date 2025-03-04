<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\MailingListModel\Subscriber::class, function (Faker $faker) {
    return [
        'email' => $faker->email,
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'code' => \App\MailingListModel\Subscriber::generate_unique_code()
    ];
});
