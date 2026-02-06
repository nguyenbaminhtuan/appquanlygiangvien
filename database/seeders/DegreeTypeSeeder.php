<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\DegreeType;

class DegreeTypeSeeder extends Seeder
{
    public function run(): void
    {
        DegreeType::updateOrCreate(['name' => 'Cử nhân'], ['abbreviation' => 'CN']);
        DegreeType::updateOrCreate(['name' => 'Kỹ sư'], ['abbreviation' => 'KS']);
        DegreeType::updateOrCreate(['name' => 'Thạc sĩ'], ['abbreviation' => 'ThS']);
        DegreeType::updateOrCreate(['name' => 'Tiến sĩ'], ['abbreviation' => 'TS']);
        DegreeType::updateOrCreate(['name' => 'Phó Giáo sư'], ['abbreviation' => 'PGS']);
        DegreeType::updateOrCreate(['name' => 'Giáo sư'], ['abbreviation' => 'GS']);
    }
}