<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lecturers', function (Blueprint $table) {
            $table->text('academic_degrees_text')->nullable()->after('position'); // Hoặc vị trí bạn muốn
            $table->text('work_histories_text')->nullable()->after('academic_degrees_text');
        });
    }

    public function down(): void
    {
        Schema::table('lecturers', function (Blueprint $table) {
            $table->dropColumn(['academic_degrees_text', 'work_histories_text']);
        });
    }
};