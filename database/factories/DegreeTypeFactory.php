<?php

namespace Database\Factories;

use App\Models\DegreeType;
use Illuminate\Database\Eloquent\Factories\Factory;

class DegreeTypeFactory extends Factory
{
    protected $model = DegreeType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(['Cử nhân', 'Thạc sĩ', 'Tiến sĩ', 'Phó Giáo sư', 'Giáo sư']),
            'abbreviation' => $this->faker->unique()->lexify('??'),
            'description' => $this->faker->sentence(),
        ];
    }
}