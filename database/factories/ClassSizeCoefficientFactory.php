<?php

namespace Database\Factories;

use App\Models\ClassSizeCoefficient;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassSizeCoefficientFactory extends Factory
{
    protected $model = ClassSizeCoefficient::class;

    public function definition(): array
    {
        return [
            'min_students' => $this->faker->randomElement([0, 20, 30, 40]),
            'max_students' => $this->faker->randomElement([30, 40, 50, 60]),
            'coefficient' => $this->faker->randomFloat(2, 0.8, 1.5),
        ];
    }
}