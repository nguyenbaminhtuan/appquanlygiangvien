<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lecturer_pay_rates', function (Blueprint $table) {
            $table->id();
            // Tên trình độ/chức danh để áp dụng hệ số này
            // Ví dụ: "Đại học", "Thạc sỹ", "Tiến sỹ", "Phó Giáo sư", "Giáo sư"
            // Bạn có thể muốn liên kết với degree_types.id nếu tên trong degree_types phù hợp
            // Hoặc để là một trường VARCHAR độc lập nếu danh mục này khác.
            // Để đơn giản ban đầu, chúng ta dùng VARCHAR.
            $table->string('academic_level_or_title')->unique();
            $table->decimal('coefficient', 5, 2); // Hệ số giáo viên, ví dụ: 1.30, 1.50
            $table->date('effective_date')->comment('Ngày bắt đầu áp dụng');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lecturer_pay_rates');
    }
};