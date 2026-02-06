<?php

namespace Database\Factories;

use App\Models\Subject;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
{
    protected $model = Subject::class;

    public function definition(): array
    {
        $departmentIds = Department::pluck('id')->toArray();

        return [
            'department_id' => !empty($departmentIds) ? $this->faker->randomElement($departmentIds) : null,
            'subject_code' => 'MH' . $this->faker->unique()->numberBetween(100, 999),
            'name' => $this->faker->words(3, true),
            'credits' => $this->faker->randomElement([2, 3, 4]),
            'default_teaching_hours' => $this->faker->randomElement([30, 45, 60]),
            'subject_coefficient' => $this->faker->randomFloat(2, 1.0, 2.0),
            'description' => $this->faker->sentence(),
        ];
    }
}