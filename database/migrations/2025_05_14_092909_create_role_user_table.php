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
        Schema::create('role_user', function (Blueprint $table) {
            $table->id(); // Cột ID tự tăng, khóa chính
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Khóa ngoại user_id, liên kết với cột id của bảng 'users'
            // onDelete('cascade'): Nếu một user bị xóa, các bản ghi liên quan trong bảng role_user cũng sẽ bị xóa

            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            // Khóa ngoại role_id, liên kết với cột id của bảng 'roles'
            // onDelete('cascade'): Nếu một role bị xóa, các bản ghi liên quan trong bảng role_user cũng sẽ bị xóa

            // Đảm bảo một cặp user_id và role_id là duy nhất,
            // nghĩa là một người dùng không thể có cùng một vai trò nhiều lần.
            $table->unique(['user_id', 'role_id']);

            $table->timestamps(); // Tự động tạo cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_user');
    }
};