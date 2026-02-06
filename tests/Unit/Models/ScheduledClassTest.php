<?php

namespace Tests\Unit\Models;

use App\Models\ScheduledClass;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\Lecturer;
use App\Models\AcademicYear;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScheduledClassTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        AcademicYear::factory()->count(1)->create();
        Semester::factory()->count(1)->create();
        Subject::factory()->count(1)->create();
        Lecturer::factory()->count(1)->create();
    }

    public function test_scheduled_class_belongs_to_semester(): void
    {
        $class = ScheduledClass::factory()->create();
        $this->assertInstanceOf(Semester::class, $class->semester);
    }

    public function test_scheduled_class_belongs_to_subject(): void
    {
        $class = ScheduledClass::factory()->create();
        $this->assertInstanceOf(Subject::class, $class->subject);
    }

    public function test_scheduled_class_belongs_to_lecturer(): void
    {
        $class = ScheduledClass::factory()->create();
        $this->assertInstanceOf(Lecturer::class, $class->lecturer);
    }

    public function test_scheduled_class_factory_creates_valid_class(): void
    {
        $class = ScheduledClass::factory()->create();
        $this->assertNotNull($class->class_code);
    }
}