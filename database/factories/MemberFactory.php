<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\EcommerceModel\Member;
use App\Models\User;
use Faker\Generator as Faker;


$factory->define(Member::class, function (Faker $faker) {
    $memberIds = Member::all()->pluck('id');
    $user = User::where('user_type', 'member')->whereNotIn('user_id', $memberIds)->first();

    $street = $faker->streetName;
    $brgy = $faker->secondaryAddress;
    $city = $faker->city;
    $province = $faker->state;
    $zip = $faker->postcode;

    return [
        'user_id' => $user->id,
        'name' => $user->name,
        'code' => 'member100'.$user->id,
        'email' => $user->email,
        'mobile' => '09748392284',
        'phone' => '044 8 795 2234',
        'address_street' => $street,
        'address_brgy' => $brgy,
        'address_city' => $city,
        'address_province' => $province,
        'address_zip' => $zip,
        'address_delivery_street' => $street,
        'address_delivery_brgy' => $brgy,
        'address_delivery_city' => $city,
        'address_delivery_province' => $province,
        'address_delivery_zip' => $zip,
        'status' => 'active',
        'class' => 'regular',
    ];
});
