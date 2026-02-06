<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Số tiết thực tế mặc định cho học phần này (ví dụ: cho lớp lý thuyết)
            $table->unsignedInteger('default_teaching_hours')->default(30)->after('credits');
            // Hệ số học phần (ví dụ: 1.0 đến 1.5)
            $table->decimal('subject_coefficient', 3, 2)->default(1.0)->after('default_teaching_hours');
        });
    }

    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn(['default_teaching_hours', 'subject_coefficient']);
        });
    }
};