<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Lecturer;
use App\Models\AcademicDegree;
use App\Models\DegreeType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LecturerAcademicDegreeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        DegreeType::factory()->create(['name' => 'Tiến sĩ', 'abbreviation' => 'TS']);
        DegreeType::factory()->create(['name' => 'Thạc sĩ', 'abbreviation' => 'ThS']);
    }

    public function test_can_show_create_form(): void
    {
        $user = User::factory()->create();
        $lecturer = Lecturer::factory()->create();

        $response = $this->actingAs($user)->get('/admin/lecturers/' . $lecturer->id . '/academic-degrees/create');

        $response->assertStatus(200);
    }

    public function test_can_store_academic_degree(): void
    {
        $user = User::factory()->create();
        $lecturer = Lecturer::factory()->create();
        $degreeType = DegreeType::first();

        $response = $this->actingAs($user)->post('/admin/lecturers/' . $lecturer->id . '/academic-degrees', [
            'degree_type_id' => $degreeType->id,
            'specialization' => 'Khoa học máy tính',
            'issuing_institution' => 'Đại học Quốc gia',
            'date_issued' => '2020-05-15',
            'notes' => 'Bằng tốt nghiệp loại Giỏi',
        ]);

        $response->assertRedirect('/admin/lecturers/' . $lecturer->id . '/edit');
        $this->assertDatabaseHas('academic_degrees', [
            'lecturer_id' => $lecturer->id,
            'specialization' => 'Khoa học máy tính',
        ]);
    }

    public function test_store_validation_fails_with_missing_fields(): void
    {
        $user = User::factory()->create();
        $lecturer = Lecturer::factory()->create();

        $response = $this->actingAs($user)->post('/admin/lecturers/' . $lecturer->id . '/academic-degrees', [
            'degree_type_id' => '',
            'specialization' => '',
        ]);

        $response->assertSessionHasErrors(['degree_type_id', 'specialization']);
    }

    public function test_can_show_edit_form(): void
    {
        $user = User::factory()->create();
        $lecturer = Lecturer::factory()->create();
        $degreeType = DegreeType::first();
        $academicDegree = AcademicDegree::factory()->create([
            'lecturer_id' => $lecturer->id,
            'degree_type_id' => $degreeType->id,
        ]);

        $response = $this->actingAs($user)->get('/admin/lecturers/' . $lecturer->id . '/academic-degrees/' . $academicDegree->id . '/edit');

        $response->assertStatus(200);
    }

    public function test_can_update_academic_degree(): void
    {
        $user = User::factory()->create();
        $lecturer = Lecturer::factory()->create();
        $degreeType = DegreeType::first();
        $academicDegree = AcademicDegree::factory()->create([
            'lecturer_id' => $lecturer->id,
            'degree_type_id' => $degreeType->id,
            'specialization' => 'Toán học',
        ]);

        $response = $this->actingAs($user)->put('/admin/lecturers/' . $lecturer->id . '/academic-degrees/' . $academicDegree->id, [
            'degree_type_id' => $degreeType->id,
            'specialization' => 'Vật lý học',
            'issuing_institution' => 'Đại học Khoa học',
            'date_issued' => '2019-06-20',
        ]);

        $response->assertRedirect('/admin/lecturers/' . $lecturer->id . '/edit');
        $this->assertDatabaseHas('academic_degrees', ['specialization' => 'Vật lý học']);
    }

    public function test_can_delete_academic_degree(): void
    {
        $user = User::factory()->create();
        $lecturer = Lecturer::factory()->create();
        $degreeType = DegreeType::first();
        $academicDegree = AcademicDegree::factory()->create([
            'lecturer_id' => $lecturer->id,
            'degree_type_id' => $degreeType->id,
        ]);
        $degreeId = $academicDegree->id;

        $response = $this->actingAs($user)->delete('/admin/lecturers/' . $lecturer->id . '/academic-degrees/' . $degreeId);

        $response->assertRedirect('/admin/lecturers/' . $lecturer->id . '/edit');
        $this->assertDatabaseMissing('academic_degrees', ['id' => $degreeId]);
    }

    public function test_cannot_edit_degree_from_other_lecturer(): void
    {
        $user = User::factory()->create();
        $lecturer1 = Lecturer::factory()->create();
        $lecturer2 = Lecturer::factory()->create();
        $degreeType = DegreeType::first();
        $academicDegree = AcademicDegree::factory()->create([
            'lecturer_id' => $lecturer1->id,
            'degree_type_id' => $degreeType->id,
        ]);

        $response = $this->actingAs($user)->get('/admin/lecturers/' . $lecturer2->id . '/academic-degrees/' . $academicDegree->id . '/edit');

        $response->assertStatus(404);
    }
}