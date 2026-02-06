<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory
{
    private static int $deptCounter = 1;

    public function definition(): array
    {
        $num = self::$deptCounter++;

        return [
            'name' => 'Khoa ' . $num,
            'code' => 'K' . str_pad($num, 3, '0', STR_PAD_LEFT),
            'description' => fake()->optional()->sentence(),
        ];
    }
}
