// database/migrations/xxxx_xx_xx_xxxxxx_create_degree_types_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('degree_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Ví dụ: Cử nhân, Thạc sĩ, Tiến sĩ
            $table->string('abbreviation')->unique()->nullable(); // Ví dụ: CN, ThS, TS
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('degree_types');
    }
};