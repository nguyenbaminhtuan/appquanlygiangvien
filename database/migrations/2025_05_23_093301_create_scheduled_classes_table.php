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
        Schema::create('scheduled_classes', function (Blueprint $table) {
           $table->id();
    $table->foreignId('semester_id')->constrained('semesters')->onDelete('cascade');
    $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
    $table->string('class_code'); // Ví dụ: IT101.N01
    $table->foreignId('lecturer_id')->nullable()->constrained('lecturers')->onDelete('set null');
    $table->unsignedInteger('max_students')->default(50);
    $table->unsignedInteger('current_students')->default(0);
    $table->text('schedule_info')->nullable(); // Thứ, Giờ, Phòng
    $table->text('notes')->nullable();
    $table->timestamps();

    $table->unique(['semester_id', 'subject_id', 'class_code']); // Đảm bảo mã lớp là duy nhất trong kỳ và học phần
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduled_classes');
    }
};
