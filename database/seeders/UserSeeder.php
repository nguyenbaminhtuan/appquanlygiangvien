<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Import model User
use App\Models\Role; // Import model Role
use Illuminate\Support\Facades\Hash; // Import Hash facade

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tìm role 'admin'
        $adminRole = Role::where('name', 'admin')->first();
        $managerRole = Role::where('name', 'lecturer_manager')->first();

        // Tạo người dùng Admin
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'), // Đặt mật khẩu là 'password'
            ]
        );
        // Gán vai trò admin cho người dùng admin
        if ($adminUser && $adminRole) {
            $adminUser->roles()->syncWithoutDetaching([$adminRole->id]);
        }

        // Tạo người dùng Quản lý Giảng viên
        $managerUser = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Lecturer Manager',
                'password' => Hash::make('password'),
            ]
        );
        // Gán vai trò lecturer_manager
        if ($managerUser && $managerRole) {
            $managerUser->roles()->syncWithoutDetaching([$managerRole->id]);
        }

        // Bạn có thể tạo thêm người dùng ở đây nếu muốn
    }
}