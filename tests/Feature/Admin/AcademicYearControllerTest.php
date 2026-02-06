<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\AcademicYear;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademicYearControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_page_loads(): void
    {
        $user = User::factory()->create();
        AcademicYear::factory()->count(2)->create();

        $response = $this->actingAs($user)->get('/admin/academic-years');

        $response->assertStatus(200);
    }

    public function test_create_page_loads(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/academic-years/create');

        $response->assertStatus(200);
    }

    public function test_can_store_academic_year(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/admin/academic-years', [
            'name' => '2025 - 2026',
            'start_date' => '2025-09-01',
            'end_date' => '2026-06-30',
        ]);

        $response->assertRedirect('/admin/academic-years');
        $this->assertDatabaseHas('academic_years', ['name' => '2025 - 2026']);
    }

    public function test_store_validation_fails(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/admin/academic-years', [
            'name' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'start_date', 'end_date']);
    }

    public function test_can_update_academic_year(): void
    {
        $user = User::factory()->create();
        $year = AcademicYear::factory()->create();

        $response = $this->actingAs($user)->put('/admin/academic-years/' . $year->id, [
            'name' => 'Updated Year',
            'start_date' => $year->start_date->format('Y-m-d'),
            'end_date' => $year->end_date->format('Y-m-d'),
        ]);

        $response->assertRedirect('/admin/academic-years');
        $this->assertDatabaseHas('academic_years', ['name' => 'Updated Year']);
    }

    public function test_can_delete_academic_year(): void
    {
        $user = User::factory()->create();
        $year = AcademicYear::factory()->create();
        $yearId = $year->id;

        $response = $this->actingAs($user)->delete('/admin/academic-years/' . $yearId);

        $response->assertRedirect('/admin/academic-years');
        $this->assertDatabaseMissing('academic_years', ['id' => $yearId]);
    }
}