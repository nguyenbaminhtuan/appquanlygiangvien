<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\ClassSizeCoefficient;

class ClassSizeCoefficientSeeder extends Seeder
{
    public function run(): void
    {
        ClassSizeCoefficient::updateOrCreate(['min_students' => 0, 'max_students' => 19], ['coefficient' => -0.30]);
        ClassSizeCoefficient::updateOrCreate(['min_students' => 20, 'max_students' => 29], ['coefficient' => -0.20]);
        ClassSizeCoefficient::updateOrCreate(['min_students' => 30, 'max_students' => 39], ['coefficient' => -0.10]);
        ClassSizeCoefficient::updateOrCreate(['min_students' => 40, 'max_students' => 49], ['coefficient' => 0.00]);
        ClassSizeCoefficient::updateOrCreate(['min_students' => 50, 'max_students' => 59], ['coefficient' => 0.10]);
        ClassSizeCoefficient::updateOrCreate(['min_students' => 60, 'max_students' => 69], ['coefficient' => 0.20]);
        ClassSizeCoefficient::updateOrCreate(['min_students' => 70, 'max_students' => 79], ['coefficient' => 0.30]);
        ClassSizeCoefficient::updateOrCreate(['min_students' => 80, 'max_students' => null], ['coefficient' => 0.40]);
    }
}