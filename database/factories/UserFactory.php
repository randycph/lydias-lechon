<?php

namespace Database\Factories;

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

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            "contact_mobile" => '09175278392',
            "email" => $this->faker->email(),

            'name' => $this->faker->word(),
            'firstname' => $this->faker->firstName(),
            'lastname' => $this->faker->lastName(),
            'email' => $this->faker->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'user_type' => 'customer',
            'is_active' => 1,
            'role_id' => 6,
            'registration_type' => 'guest',
            'registration_source' => 'web',
            'valid_email' => $this->faker->lastName(),
            'remember_token' => Str::random(10),
        ];
    }
}