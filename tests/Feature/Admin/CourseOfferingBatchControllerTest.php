<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\Lecturer;
use App\Models\ScheduledClass;
use App\Models\AcademicYear;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseOfferingBatchControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_page_loads(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/course-offerings/open-batch');

        $response->assertStatus(200);
    }

    public function test_store_validation_fails_with_missing_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/admin/course-offerings/open-batch', [
            'semester_id' => '',
            'subject_id' => '',
            'number_of_classes' => '',
            'max_students_per_class' => '',
        ]);

        $response->assertSessionHasErrors(['semester_id', 'subject_id', 'number_of_classes', 'max_students_per_class']);
    }

    public function test_store_validation_fails_with_invalid_number_of_classes(): void
    {
        $user = User::factory()->create();
        $academicYear = AcademicYear::factory()->create();
        $semester = Semester::factory()->create(['academic_year_id' => $academicYear->id]);
        $subject = Subject::factory()->create();

        $response = $this->actingAs($user)->post('/admin/course-offerings/open-batch', [
            'semester_id' => $semester->id,
            'subject_id' => $subject->id,
            'number_of_classes' => 0,
            'max_students_per_class' => 50,
        ]);

        $response->assertSessionHasErrors(['number_of_classes']);
    }

    public function test_store_validation_fails_with_invalid_max_students(): void
    {
        $user = User::factory()->create();
        $academicYear = AcademicYear::factory()->create();
        $semester = Semester::factory()->create(['academic_year_id' => $academicYear->id]);
        $subject = Subject::factory()->create();

        $response = $this->actingAs($user)->post('/admin/course-offerings/open-batch', [
            'semester_id' => $semester->id,
            'subject_id' => $subject->id,
            'number_of_classes' => 2,
            'max_students_per_class' => 250,
        ]);

        $response->assertSessionHasErrors(['max_students_per_class']);
    }

    public function test_post_redirects_after_successful_submission(): void
    {
        $user = User::factory()->create();
        $academicYear = AcademicYear::factory()->create();
        $semester = Semester::factory()->create(['academic_year_id' => $academicYear->id]);
        $subject = Subject::factory()->create();

        $response = $this->actingAs($user)->post('/admin/course-offerings/open-batch', [
            'semester_id' => $semester->id,
            'subject_id' => $subject->id,
            'number_of_classes' => 2,
            'max_students_per_class' => 30,
            'class_code_prefix' => 'TEST',
        ]);

        // Controller redirects to route 'admin.course-offerings.open-batch.create'
        $response->assertRedirect();
    }

    public function test_validation_requires_valid_semester(): void
    {
        $user = User::factory()->create();
        $subject = Subject::factory()->create();

        $response = $this->actingAs($user)->post('/admin/course-offerings/open-batch', [
            'semester_id' => 9999,
            'subject_id' => $subject->id,
            'number_of_classes' => 2,
            'max_students_per_class' => 30,
        ]);

        $response->assertSessionHasErrors(['semester_id']);
    }

    public function test_validation_requires_valid_subject(): void
    {
        $user = User::factory()->create();
        $academicYear = AcademicYear::factory()->create();
        $semester = Semester::factory()->create(['academic_year_id' => $academicYear->id]);

        $response = $this->actingAs($user)->post('/admin/course-offerings/open-batch', [
            'semester_id' => $semester->id,
            'subject_id' => 9999,
            'number_of_classes' => 2,
            'max_students_per_class' => 30,
        ]);

        $response->assertSessionHasErrors(['subject_id']);
    }
}