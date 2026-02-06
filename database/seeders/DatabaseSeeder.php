<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Gọi các seeder theo đúng thứ tự phụ thuộc
        $this->call([
            RoleSeeder::class,
            DepartmentSeeder::class,
            DegreeTypeSeeder::class,
            UserSeeder::class,       
            LecturerSeeder::class, 
            SettingSeeder::class,              
            LecturerPayRateSeeder::class,    
            ClassSizeCoefficientSeeder::class,  
        ]);
    }
}