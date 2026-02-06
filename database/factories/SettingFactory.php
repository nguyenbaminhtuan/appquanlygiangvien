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
            'key' => fake()->unique()->word() . '_' . fake()->randomNumber(4),
            'value' => (string) fake()->randomNumber(5),
            'type' => 'string',
        ];
    }
}