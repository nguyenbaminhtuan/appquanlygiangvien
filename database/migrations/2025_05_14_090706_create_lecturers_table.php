<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lecturers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained('users')->onDelete('set null'); // Hoặc onDelete('cascade') nếu muốn xóa giảng viên khi user bị xóa
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->string('lecturer_code')->unique();
            $table->string('full_name');
            $table->date('date_of_birth');
            $table->string('gender'); // Có thể dùng enum: ->enum('gender', ['Nam', 'Nữ', 'Khác']); nếu CSDL hỗ trợ
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->text('address')->nullable();
            $table->string('academic_level'); // VD: 'Cử nhân', 'Thạc sĩ', 'Tiến sĩ'
            $table->string('position')->nullable(); // VD: 'Giảng viên', 'Trưởng bộ môn'
            $table->string('avatar')->nullable(); // Đường dẫn tới file ảnh
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lecturers');
    }
};