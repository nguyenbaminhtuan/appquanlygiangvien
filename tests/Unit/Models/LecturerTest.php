<?php

namespace Tests\Unit\Models;

use App\Models\Lecturer;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LecturerTest extends TestCase
{
    use RefreshDatabase;

    public function test_lecturer_belongs_to_department(): void
    {
        $department = Department::factory()->create();
        $lecturer = Lecturer::factory()->create(['department_id' => $department->id]);

        $this->assertInstanceOf(Department::class, $lecturer->department);
        $this->assertEquals($department->id, $lecturer->department->id);
    }

    public function test_lecturer_has_many_academic_degrees(): void
    {
        $lecturer = Lecturer::factory()->create();
        $this->assertTrue(method_exists($lecturer, 'academicDegrees'));
    }

    public function test_lecturer_has_many_work_histories(): void
    {
        $lecturer = Lecturer::factory()->create();
        $this->assertTrue(method_exists($lecturer, 'workHistories'));
    }

    public function test_lecturer_has_many_scheduled_classes(): void
    {
        $lecturer = Lecturer::factory()->create();
        $this->assertTrue(method_exists($lecturer, 'scheduledClasses'));
    }

    public function test_lecturer_factory_creates_valid_lecturer(): void
    {
        $lecturer = Lecturer::factory()->create();

        $this->assertNotNull($lecturer->full_name);
        $this->assertNotNull($lecturer->lecturer_code);
        $this->assertNotNull($lecturer->email);
    }
}