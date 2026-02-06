<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use Illuminate\Database\Eloquent\Factories\Factory;

class AcademicYearFactory extends Factory
{
    protected $model = AcademicYear::class;

    private static int $yearOffset = 0;

    public function definition(): array
    {
        $year = (int) date('Y') + self::$yearOffset++;

        return [
            'name' => (string) $year . ' - ' . (string) ($year + 1),
            'start_date' => $this->faker->dateTimeBetween($year . '-09-01', $year . '-09-30'),
            'end_date' => $this->faker->dateTimeBetween(($year + 1) . '-05-31', ($year + 1) . '-06-30'),
        ];
    }
}