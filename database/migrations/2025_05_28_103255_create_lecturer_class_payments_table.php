<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lecturer_class_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lecturer_id')->constrained('lecturers')->onDelete('cascade');
            $table->foreignId('scheduled_class_id')->constrained('scheduled_classes')->onDelete('cascade');
            $table->foreignId('semester_id')->constrained('semesters')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');

            $table->unsignedInteger('actual_teaching_hours_snapshot');
            $table->decimal('subject_coefficient_snapshot', 3, 2);
            $table->decimal('class_size_coefficient_snapshot', 3, 2);
            $table->decimal('lecturer_coefficient_snapshot', 5, 2);
            $table->decimal('base_rate_snapshot', 15, 2); // Tiền dạy một tiết
            $table->decimal('converted_teaching_units', 8, 2); // Số tiết quy đổi
            $table->decimal('payment_amount', 15, 2); // Tiền dạy cho lớp này

            $table->dateTime('calculation_date');
            $table->enum('status', ['calculated', 'approved', 'paid', 'rejected', 'pending'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('lecturer_class_payments');
    }
};