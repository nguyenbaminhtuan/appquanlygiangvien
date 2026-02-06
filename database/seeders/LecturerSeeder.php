<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lecturer;
use App\Models\AcademicDegree; // Import
use App\Models\WorkHistory;    // Import
use App\Models\Department;    // Import

class LecturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kiểm tra xem có khoa nào không, nếu không thì không tạo giảng viên
        if (Department::count() == 0) {
            $this->command->info('No departments found, skipping lecturer seeding.');
            return;
        }

        Lecturer::factory(20)->create()->each(function ($lecturer) {
            // Tạo 1-3 học vị cho mỗi giảng viên
            AcademicDegree::factory(rand(1, 3))->create([
                'lecturer_id' => $lecturer->id,
            ]);

            // Tạo 1-4 mục quá trình công tác cho mỗi giảng viên
            WorkHistory::factory(rand(1, 4))->create([
                'lecturer_id' => $lecturer->id,
            ]);
        });

        // Tạo một giảng viên cụ thể có liên kết với user 'manager@example.com' (nếu có)
        $managerUser = \App\Models\User::where('email', 'manager@example.com')->first();
        $cnttDepartment = Department::where('code', 'CNTT')->first();

        if ($managerUser && $cnttDepartment && !Lecturer::where('user_id', $managerUser->id)->exists()) {
            Lecturer::factory()->create([
                'user_id' => $managerUser->id,
                'department_id' => $cnttDepartment->id,
                'full_name' => $managerUser->name,
                'email' => $managerUser->email, // Có thể dùng email khác nếu muốn
                'lecturer_code' => 'GVQL001',
                'academic_level' => 'Thạc sĩ',
                'position' => 'Quản lý Khoa'
            ])->each(function ($lecturer) {
                 AcademicDegree::factory(rand(1,2))->create(['lecturer_id' => $lecturer->id]);
                 WorkHistory::factory(rand(1,2))->create(['lecturer_id' => $lecturer->id]);
            });
        }
    }
}