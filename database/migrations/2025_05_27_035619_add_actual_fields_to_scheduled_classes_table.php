<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scheduled_classes', function (Blueprint $table) {
            // Sĩ số thực tế của lớp, có thể cập nhật sau
            $table->unsignedInteger('actual_students')->nullable()->after('max_students');
            // Số tiết thực tế cho lớp này (có thể khác default_teaching_hours của subject)
            $table->unsignedInteger('actual_teaching_hours')->nullable()->after('actual_students');
        });
    }

    public function down(): void
    {
        Schema::table('scheduled_classes', function (Blueprint $table) {
            $table->dropColumn(['actual_students', 'actual_teaching_hours']);
        });
    }
};