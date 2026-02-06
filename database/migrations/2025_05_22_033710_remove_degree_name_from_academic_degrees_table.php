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
    Schema::table('academic_degrees', function (Blueprint $table) {
        if (Schema::hasColumn('academic_degrees', 'degree_name')) { // Kiểm tra nếu cột tồn tại
            $table->dropColumn('degree_name');
        }
    });
}

public function down(): void
{
    Schema::table('academic_degrees', function (Blueprint $table) {
        $table->string('degree_name')->nullable(); // Thêm lại nếu rollback
    });
}
};
