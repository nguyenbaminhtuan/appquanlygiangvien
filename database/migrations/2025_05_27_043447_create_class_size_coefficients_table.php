<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_size_coefficients', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('min_students');
            $table->unsignedInteger('max_students')->nullable(); // Nullable cho trường hợp không có giới hạn trên
            $table->decimal('coefficient', 3, 2); // Hệ số lớp, ví dụ: -0.30, 0.10
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_size_coefficients');
    }
};