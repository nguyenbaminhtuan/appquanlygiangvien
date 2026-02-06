<?php

namespace Database\Factories;

use App\Models\LecturerClassPayment;
use App\Models\Lecturer;
use App\Models\ScheduledClass;
use App\Models\Semester;
use App\Models\AcademicYear;
use Illuminate\Database\Eloquent\Factories\Factory;

class LecturerClassPaymentFactory extends Factory
{
    protected $model = LecturerClassPayment::class;

    public function definition(): array
    {
        $semesterIds = Semester::pluck('id')->toArray();
        $lecturerIds = Lecturer::pluck('id')->toArray();
        $scheduledClassIds = ScheduledClass::pluck('id')->toArray();
        $academicYearIds = AcademicYear::pluck('id')->toArray();

        $baseRate = $this->faker->randomFloat(2, 50000, 150000);
        $subjectCoeff = $this->faker->randomFloat(2, 1.0, 2.0);
        $classSizeCoeff = $this->faker->randomFloat(2, 0.8, 1.5);
        $lecturerCoeff = $this->faker->randomFloat(2, 1.0, 3.0);
        $teachingHours = $this->faker->randomElement([30, 45, 60]);
        $convertedUnits = $teachingHours * $subjectCoeff * $classSizeCoeff * $lecturerCoeff / 45;

        return [
            'lecturer_id' => !empty($lecturerIds) ? $this->faker->randomElement($lecturerIds) : null,
            'scheduled_class_id' => !empty($scheduledClassIds) ? $this->faker->randomElement($scheduledClassIds) : null,
            'semester_id' => !empty($semesterIds) ? $this->faker->randomElement($semesterIds) : null,
            'academic_year_id' => !empty($academicYearIds) ? $this->faker->randomElement($academicYearIds) : null,
            'actual_teaching_hours_snapshot' => $teachingHours,
            'subject_coefficient_snapshot' => $subjectCoeff,
            'class_size_coefficient_snapshot' => $classSizeCoeff,
            'lecturer_coefficient_snapshot' => $lecturerCoeff,
            'base_rate_snapshot' => $baseRate,
            'converted_teaching_units' => round($convertedUnits, 2),
            'payment_amount' => round($baseRate * $convertedUnits, 2),
            'calculation_date' => now(),
            'status' => $this->faker->randomElement(['pending', 'calculated', 'paid']),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}