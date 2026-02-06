<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\ScheduledClass;
use App\Models\Lecturer;
use App\Models\Subject;
use App\Models\Semester;
use App\Models\AcademicYear;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScheduledClassControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Department::factory()->count(2)->create();
        AcademicYear::factory()->count(1)->create();
        Semester::factory()->count(1)->create();
        Lecturer::factory()->count(3)->create();
        Subject::factory()->count(2)->create();
    }

    public function test_index_page_loads(): void
    {
        $user = User::factory()->create();
        ScheduledClass::factory()->count(3)->create();

        $response = $this->actingAs($user)->get('/admin/scheduled-classes');

        $response->assertStatus(200);
    }

    public function test_create_page_loads(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/scheduled-classes/create');

        $response->assertStatus(200);
    }

    public function test_can_store_scheduled_class(): void
    {
        $this->markTestSkipped('ScheduledClassController::store() is not implemented');
    }

    public function test_show_page_loads(): void
    {
        $user = User::factory()->create();
        $class = ScheduledClass::factory()->create();

        $response = $this->actingAs($user)->get('/admin/scheduled-classes/' . $class->id);

        $response->assertStatus(200);
    }

    public function test_can_update_scheduled_class(): void
    {
        $user = User::factory()->create();
        $class = ScheduledClass::factory()->create();

        $response = $this->actingAs($user)->put('/admin/scheduled-classes/' . $class->id, [
            'semester_id' => $class->semester_id,
            'subject_id' => $class->subject_id,
            'lecturer_id' => $class->lecturer_id,
            'class_code' => 'UPDATED',
            'max_students' => 50,
            'actual_students' => 45,
            'actual_teaching_hours' => 60,
            'schedule_info' => 'T4 - 09:00',
        ]);

        $response->assertRedirect('/admin/scheduled-classes');
        $this->assertDatabaseHas('scheduled_classes', ['class_code' => 'UPDATED']);
    }

    public function test_can_delete_scheduled_class(): void
    {
        $user = User::factory()->create();
        $class = ScheduledClass::factory()->create();
        $classId = $class->id;

        $response = $this->actingAs($user)->delete('/admin/scheduled-classes/' . $classId);

        $response->assertRedirect('/admin/scheduled-classes');
        $this->assertDatabaseMissing('scheduled_classes', ['id' => $classId]);
    }
}