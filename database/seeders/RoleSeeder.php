<?php

   namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role; // Quan trọng: Import model Role

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Quản trị viên']);
        Role::firstOrCreate(['name' => 'lecturer_manager'], ['display_name' => 'Quản lý giảng viên']);
        // Role::firstOrCreate(['name' => 'lecturer'], ['display_name' => 'Giảng viên']); // Có thể thêm nếu giảng viên cũng là user
    }
}
