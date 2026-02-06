<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id(); // Khóa chính, tự tăng
            $table->string('name')->unique(); // Tên năm học, ví dụ: "Năm học 2024-2025", không trùng lặp
            $table->date('start_date');     // Ngày bắt đầu năm học
            $table->date('end_date');       // Ngày kết thúc năm học
            $table->timestamps();           // Tự động tạo cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_years');
    }
};