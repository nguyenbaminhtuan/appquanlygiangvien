<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::updateOrCreate(
            ['key' => 'base_rate_per_teaching_unit'],
            ['value' => '160000', 'type' => 'decimal']
        );
    }
}