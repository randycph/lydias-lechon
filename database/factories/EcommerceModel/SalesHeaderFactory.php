<?php

namespace Database\Factories\EcommerceModel;

use App\EcommerceModel\SalesHeader;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

class SalesHeaderFactory extends Factory
{
    protected $model = SalesHeader::class;

    public function definition(): array
    {
        return [
            'user_id' => 1,
        ];
    }
}