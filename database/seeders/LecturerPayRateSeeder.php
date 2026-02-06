<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\LecturerPayRate;

class LecturerPayRateSeeder extends Seeder
{
    public function run(): void
    {
        LecturerPayRate::updateOrCreate(['academic_level_or_title' => 'Đại học'], ['coefficient' => 1.30, 'effective_date' => now()]);
        LecturerPayRate::updateOrCreate(['academic_level_or_title' => 'Thạc sỹ'], ['coefficient' => 1.50, 'effective_date' => now()]);
        LecturerPayRate::updateOrCreate(['academic_level_or_title' => 'Tiến sỹ'], ['coefficient' => 1.70, 'effective_date' => now()]);
        LecturerPayRate::updateOrCreate(['academic_level_or_title' => 'Phó Giáo sư'], ['coefficient' => 2.00, 'effective_date' => now()]);
        LecturerPayRate::updateOrCreate(['academic_level_or_title' => 'Giáo sư'], ['coefficient' => 2.50, 'effective_date' => now()]);
    }
}