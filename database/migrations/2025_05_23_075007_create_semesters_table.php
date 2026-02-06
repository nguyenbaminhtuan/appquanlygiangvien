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
        Schema::create('semesters', function (Blueprint $table) {
            $table->id(); // Khóa chính, tự tăng
            $table->foreignId('academic_year_id') // Khóa ngoại
                  ->constrained('academic_years') // Tham chiếu đến bảng academic_years
                  ->onDelete('cascade'); // Nếu năm học bị xóa, các kì học liên quan cũng sẽ bị xóa

            $table->string('name'); // Tên kì học, ví dụ: "Học kì 1", "Học kì Hè"
            $table->date('start_date');     // Ngày bắt đầu kì học
            $table->date('end_date');       // Ngày kết thúc kì học
            $table->boolean('is_current')->default(false); // Đánh dấu đây có phải là kì học hiện tại không
            $table->timestamps();           // Tự động tạo cột created_at và updated_at

            // Đảm bảo tên kì học là duy nhất trong phạm vi một năm học
            $table->unique(['academic_year_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semesters');
    }
};
