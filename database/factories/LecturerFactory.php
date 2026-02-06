<?php

namespace Database\Factories;

use App\Models\Lecturer;
use App\Models\Department;
use App\Models\User; // Để có thể liên kết với user nếu cần
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LecturerFactory extends Factory
{
    protected $model = Lecturer::class;

    public function definition(): array
    {
        // Lấy ngẫu nhiên một department_id hoặc null
        $departmentIds = Department::pluck('id')->toArray();
        $departmentId = !empty($departmentIds) ? $this->faker->optional()->randomElement($departmentIds) : null;

        // Lấy ngẫu nhiên một user_id chưa được gán cho giảng viên nào khác hoặc null
        // (Logic này có thể phức tạp hơn nếu bạn muốn đảm bảo 1-1 chặt chẽ và có nhiều user)
        // $userId = User::doesntHave('lecturerProfile')->inRandomOrder()->first()?->id;

        return [
            // 'user_id' => $userId, // Tạm thời để null hoặc logic đơn giản
            'department_id' => $departmentId,
            'lecturer_code' => 'GV' . Str::upper(Str::random(6)),
            'full_name' => $this->faker->name(),
            'date_of_birth' => $this->faker->dateTimeBetween('-60 years', '-22 years')->format('Y-m-d'),
            'gender' => $this->faker->randomElement(['Nam', 'Nữ']),
            'email' => $this->faker->unique()->safeEmail(),
            'phone_number' => $this->faker->unique()->numerify('09########'),
            'address' => $this->faker->address(),
            'academic_level' => $this->faker->randomElement(['Cử nhân', 'Thạc sĩ', 'Tiến sĩ']),
            'position' => $this->faker->randomElement(['Giảng viên', 'Giảng viên chính', 'Trợ giảng']),
            'avatar' => null, // $this->faker->imageUrl(200, 200, 'people'), // Nếu muốn ảnh ngẫu nhiên
        ];
    }
}