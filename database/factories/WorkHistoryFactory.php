<?php

namespace Database\Factories;

use App\Models\WorkHistory;
use App\Models\Lecturer;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkHistoryFactory extends Factory
{
    protected $model = WorkHistory::class;

    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-15 years', '-1 year');
        $endDate = $this->faker->optional(0.7)->dateTimeBetween($startDate, 'now'); // 70% có ngày kết thúc

        return [
            // lecturer_id sẽ được gán khi gọi factory từ LecturerSeeder
            'organization_name' => $this->faker->company(),
            'position_held' => $this->faker->jobTitle(),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate ? $endDate->format('Y-m-d') : null,
            'courses_taught' => $this->faker->optional()->bs() . ', ' . $this->faker->bs(),
            'description' => $this->faker->optional()->paragraph(),
        ];
    }
}