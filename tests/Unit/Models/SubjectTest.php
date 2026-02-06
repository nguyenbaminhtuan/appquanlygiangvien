<?php

namespace Tests\Unit\Models;

use App\Models\Subject;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_subject_belongs_to_department(): void
    {
        $department = Department::factory()->create();
        $subject = Subject::factory()->create(['department_id' => $department->id]);

        $this->assertInstanceOf(Department::class, $subject->department);
    }

    public function test_subject_has_many_scheduled_classes(): void
    {
        $subject = Subject::factory()->create();
        $this->assertTrue(method_exists($subject, 'scheduledClasses'));
    }

    public function test_subject_factory_creates_valid_subject(): void
    {
        $subject = Subject::factory()->create();

        $this->assertNotNull($subject->name);
        $this->assertNotNull($subject->subject_code);
    }
}