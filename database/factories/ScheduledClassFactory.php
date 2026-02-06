<?php

namespace Database\Factories;

use App\Models\ScheduledClass;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\Lecturer;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduledClassFactory extends Factory
{
    protected $model = ScheduledClass::class;

    public function definition(): array
    {
        $semesterIds = Semester::pluck('id')->toArray();
        $subjectIds = Subject::pluck('id')->toArray();
        $lecturerIds = Lecturer::pluck('id')->toArray();

        return [
            'semester_id' => !empty($semesterIds) ? $this->faker->randomElement($semesterIds) : null,
            'subject_id' => !empty($subjectIds) ? $this->faker->randomElement($subjectIds) : null,
            'class_code' => 'LP' . strtoupper($this->faker->bothify('???###')),
            'lecturer_id' => !empty($lecturerIds) ? $this->faker->randomElement($lecturerIds) : null,
            'max_students' => $this->faker->randomElement([30, 40, 45, 50]),
            'current_students' => $this->faker->numberBetween(20, 50),
            'actual_students' => $this->faker->numberBetween(20, 50),
            'actual_teaching_hours' => $this->faker->randomElement([30, 45, 60]),
            'schedule_info' => $this->faker->randomElement(['T2', 'T4', 'T6']) . ' - ' . $this->faker->time('H:i'),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}