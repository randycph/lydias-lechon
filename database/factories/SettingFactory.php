<?php

namespace Database\Factories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingFactory extends Factory
{
    protected $model = Setting::class;

    public function definition(): array
    {
        return [
            'minimum_processing_hours' => 24,
            'minimum_processing_hours_misc' => 12,
            'minimum_processing_hours_baka' => 72,
        ];
    }
}