<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Lecturer;
use App\Models\Department;
use App\Models\AcademicYear;
use App\Models\Semester;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LecturerControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Department::factory()->count(3)->create();
        AcademicYear::factory()->count(1)->create();
        Semester::factory()->count(1)->create();
    }

    public function test_index_page_loads(): void
    {
        $user = User::factory()->create();
        Lecturer::factory()->count(3)->create();

        $response = $this->actingAs($user)->get('/admin/lecturers');

        $response->assertStatus(200);
    }

    public function test_create_page_loads(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/lecturers/create');

        $response->assertStatus(200);
    }

    public function test_can_store_lecturer(): void
    {
        $user = User::factory()->create();
        $department = Department::first();

        $response = $this->actingAs($user)->post('/admin/lecturers', [
            'department_id' => $department->id,
            'lecturer_code' => 'GV001',
            'full_name' => 'Nguyễn Văn A',
            'date_of_birth' => '1990-01-01',
            'gender' => 'Nam',
            'email' => 'nguyenvana@example.com',
            'phone_number' => '0912345678',
            'address' => 'Hà Nội',
            'academic_level' => 'Thạc sĩ',
            'position' => 'Giảng viên',
        ]);

        $response->assertRedirect('/admin/lecturers');
        $this->assertDatabaseHas('lecturers', [
            'lecturer_code' => 'GV001',
            'full_name' => 'Nguyễn Văn A',
        ]);
    }

    public function test_store_validation_fails(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/admin/lecturers', [
            'full_name' => '',
            'email' => 'invalid-email',
        ]);

        $response->assertSessionHasErrors(['full_name', 'email']);
    }

    public function test_show_page_loads(): void
    {
        $user = User::factory()->create();
        $lecturer = Lecturer::factory()->create();

        $response = $this->actingAs($user)->get('/admin/lecturers/' . $lecturer->id);

        $response->assertStatus(200);
    }

    public function test_edit_page_loads(): void
    {
        $user = User::factory()->create();
        $lecturer = Lecturer::factory()->create();

        $response = $this->actingAs($user)->get('/admin/lecturers/' . $lecturer->id . '/edit');

        $response->assertStatus(200);
    }

    public function test_can_update_lecturer(): void
    {
        $user = User::factory()->create();
        $lecturer = Lecturer::factory()->create();

        $response = $this->actingAs($user)->put('/admin/lecturers/' . $lecturer->id, [
            'department_id' => $lecturer->department_id,
            'lecturer_code' => $lecturer->lecturer_code,
            'full_name' => 'Updated Name',
            'date_of_birth' => $lecturer->date_of_birth,
            'gender' => $lecturer->gender,
            'email' => $lecturer->email,
            'phone_number' => $lecturer->phone_number,
            'address' => $lecturer->address,
            'academic_level' => $lecturer->academic_level,
            'position' => $lecturer->position,
        ]);

        $response->assertRedirectToRoute('lecturers.show', $lecturer->id);
        $this->assertDatabaseHas('lecturers', ['full_name' => 'Updated Name']);
    }

    public function test_can_delete_lecturer(): void
    {
        $user = User::factory()->create();
        $lecturer = Lecturer::factory()->create();
        $lecturerId = $lecturer->id;

        $response = $this->actingAs($user)->delete('/admin/lecturers/' . $lecturerId);

        $response->assertRedirect('/admin/lecturers');
        $this->assertDatabaseMissing('lecturers', ['id' => $lecturerId]);
    }
}