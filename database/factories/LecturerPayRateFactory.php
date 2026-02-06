<?php

namespace Database\Factories;

use App\Models\LecturerPayRate;
use Illuminate\Database\Eloquent\Factories\Factory;

class LecturerPayRateFactory extends Factory
{
    protected $model = LecturerPayRate::class;

    public function definition(): array
    {
        return [
            'academic_level_or_title' => $this->faker->randomElement([
                'Cử nhân', 'Thạc sĩ', 'Tiến sĩ',
                'Giảng viên', 'Giảng viên chính', 'Trợ giảng'
            ]),
            'coefficient' => $this->faker->randomFloat(2, 1.0, 3.0),
            'effective_date' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}