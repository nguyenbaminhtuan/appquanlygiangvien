<?php

namespace Database\Factories;

use App\Models\Semester;
use App\Models\AcademicYear;
use Illuminate\Database\Eloquent\Factories\Factory;

class SemesterFactory extends Factory
{
    protected $model = Semester::class;

    private static int $semesterCounter = 0;

    public function definition(): array
    {
        $academicYearIds = AcademicYear::pluck('id')->toArray();
        $semesterNum = self::$semesterCounter++ % 3;

        $names = ['Học kỳ 1', 'Học kỳ 2', 'Học kỳ hè'];

        return [
            'academic_year_id' => !empty($academicYearIds) ? $this->faker->randomElement($academicYearIds) : null,
            'name' => $names[$semesterNum] . ' ' . date('Y'),
            'start_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'end_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'is_current' => false,
        ];
    }

    public function current(): static
    {
        return $this->state(fn (array $attributes) => ['is_current' => true]);
    }
}