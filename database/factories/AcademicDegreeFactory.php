<?php

namespace Database\Factories;

use App\Models\AcademicDegree;
use App\Models\Lecturer;
use App\Models\DegreeType;
use Illuminate\Database\Eloquent\Factories\Factory;

class AcademicDegreeFactory extends Factory
{
    protected $model = AcademicDegree::class;

    public function definition(): array
    {
        // Lấy ngẫu nhiên một degree_type_id từ bảng degree_types
        // Đảm bảo DegreeTypeSeeder đã chạy và bảng degree_types có dữ liệu
        $degreeTypeIds = DegreeType::pluck('id')->toArray();
        $degreeTypeId = !empty($degreeTypeIds) ? $this->faker->randomElement($degreeTypeIds) : null;

        // Nếu $degreeTypeId là null (không có DegreeType nào), bạn cần xử lý
        // Ví dụ: tạo một DegreeType mặc định hoặc bỏ qua việc tạo AcademicDegree này
        // Để đơn giản, nếu không có DegreeType, factory này có thể gây lỗi nếu degree_type_id là bắt buộc.
        // Chúng ta sẽ giả định DegreeTypeSeeder đã chạy.

        return [
            // lecturer_id sẽ được gán khi gọi factory từ LecturerSeeder
            // 'degree_name' => $this->faker->randomElement(['Tiến sĩ', 'Thạc sĩ', 'Kỹ sư', 'Cử nhân']) . ' ' . $this->faker->jobTitle(), // <<--- BỎ HOẶC SỬA DÒNG NÀY
            'degree_type_id' => $degreeTypeId, // <<--- THÊM DÒNG NÀY
            'specialization' => $this->faker->bs(), // Chuyên ngành vẫn giữ nguyên
            'issuing_institution' => $this->faker->company() . ' University',
            'date_issued' => $this->faker->dateTimeBetween('-20 years', '-1 year')->format('Y-m-d'),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}