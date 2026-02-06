// database/migrations/xxxx_xx_xx_xxxxxx_modify_academic_degrees_table_for_degree_type.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('academic_degrees', function (Blueprint $table) {
            // Thêm cột mới trước khi xóa cột cũ để có thể chuyển dữ liệu nếu cần
            $table->foreignId('degree_type_id')->nullable()->after('lecturer_id')->constrained('degree_types')->onDelete('set null');
            // Bạn có thể muốn xóa cột degree_name sau khi đã chuyển dữ liệu
            // Hoặc giữ lại và đổi tên nó thành một trường ghi chú bổ sung nếu cần
            // Tạm thời, chúng ta sẽ không xóa degree_name ngay để bạn có thể chuyển dữ liệu
            // $table->dropColumn('degree_name'); // Hãy cẩn thận với việc xóa cột có dữ liệu
        });
    }

    public function down(): void
    {
        Schema::table('academic_degrees', function (Blueprint $table) {
            // $table->string('degree_name')->after('lecturer_id'); // Thêm lại nếu đã xóa
            $table->dropForeign(['degree_type_id']);
            $table->dropColumn('degree_type_id');
        });
    }
};