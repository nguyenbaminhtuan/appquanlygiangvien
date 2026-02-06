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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id(); // Khóa chính, tự tăng
            $table->foreignId('department_id')
                  ->nullable() // Học phần có thể không thuộc khoa nào cụ thể, hoặc thuộc khoa chung
                  ->constrained('departments') // Tham chiếu đến bảng departments
                  ->onDelete('set null'); // Nếu khoa bị xóa, department_id của học phần sẽ được đặt thành NULL

            $table->string('subject_code')->unique(); // Mã học phần, ví dụ: "IT101", không trùng lặp
            $table->string('name');                 // Tên học phần, ví dụ: "Nhập môn Lập trình"
            $table->unsignedTinyInteger('credits'); // Số tín chỉ, là số nguyên dương nhỏ
            $table->text('description')->nullable(); // Mô tả chi tiết về học phần
            $table->timestamps();                   // Tự động tạo cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};