<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department; // Import model Department

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::firstOrCreate(
            ['code' => 'CNTT'],
            ['name' => 'Khoa Công nghệ Thông tin', 'description' => 'Đào tạo các ngành thuộc lĩnh vực Công nghệ Thông tin.']
        );
        Department::firstOrCreate(
            ['code' => 'KT'],
            ['name' => 'Khoa Kinh tế', 'description' => 'Đào tạo các ngành thuộc lĩnh vực Kinh tế và Quản trị Kinh doanh.']
        );
        Department::firstOrCreate(
            ['code' => 'NN'],
            ['name' => 'Khoa Ngoại ngữ', 'description' => 'Đào tạo các ngành Ngôn ngữ Anh, Ngôn ngữ Nhật, v.v.']
        );
    }
}