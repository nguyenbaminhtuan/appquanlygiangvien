<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Subject;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubjectControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Department::factory()->count(2)->create();
    }

    public function test_index_page_loads(): void
    {
        $user = User::factory()->create();
        Subject::factory()->count(3)->create();

        $response = $this->actingAs($user)->get('/admin/subjects');

        $response->assertStatus(200);
    }

    public function test_create_page_loads(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/subjects/create');

        $response->assertStatus(200);
    }

    public function test_can_store_subject(): void
    {
        $user = User::factory()->create();
        $department = Department::first();

        $response = $this->actingAs($user)->post('/admin/subjects', [
            'department_id' => $department->id,
            'subject_code' => 'MH001',
            'name' => 'Toán cao cấp',
            'credits' => 3,
            'default_teaching_hours' => 45,
            'subject_coefficient' => 1.5,
            'description' => 'Môn học cơ bản',
        ]);

        $response->assertRedirect('/admin/subjects');
        $this->assertDatabaseHas('subjects', ['subject_code' => 'MH001']);
    }

    public function test_store_validation_fails(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/admin/subjects', [
            'subject_code' => '',
            'name' => '',
        ]);

        $response->assertSessionHasErrors(['subject_code', 'name']);
    }

    public function test_show_page_loads(): void
    {
        $this->markTestSkipped('SubjectController does not have show method');
    }

    public function test_edit_page_loads(): void
    {
        $user = User::factory()->create();
        $subject = Subject::factory()->create();

        $response = $this->actingAs($user)->get('/admin/subjects/' . $subject->id . '/edit');

        $response->assertStatus(200);
    }

    public function test_can_update_subject(): void
    {
        $user = User::factory()->create();
        $subject = Subject::factory()->create();

        $response = $this->actingAs($user)->put('/admin/subjects/' . $subject->id, [
            'department_id' => $subject->department_id,
            'subject_code' => $subject->subject_code,
            'name' => 'Updated Subject Name',
            'credits' => 4,
            'default_teaching_hours' => 60,
            'subject_coefficient' => 2.0,
            'description' => 'Updated description',
        ]);

        $response->assertRedirect('/admin/subjects');
        $this->assertDatabaseHas('subjects', ['name' => 'Updated Subject Name']);
    }

    public function test_can_delete_subject(): void
    {
        $user = User::factory()->create();
        $subject = Subject::factory()->create();
        $subjectId = $subject->id;

        $response = $this->actingAs($user)->delete('/admin/subjects/' . $subjectId);

        $response->assertRedirect('/admin/subjects');
        $this->assertDatabaseMissing('subjects', ['id' => $subjectId]);
    }
}