<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\DegreeType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DegreeTypeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_page_loads(): void
    {
        $user = User::factory()->create();
        DegreeType::factory()->count(3)->create();

        $response = $this->actingAs($user)->get('/admin/degree-types');

        $response->assertStatus(200);
    }

    public function test_create_page_loads(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/degree-types/create');

        $response->assertStatus(200);
    }

    public function test_can_store_degree_type(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/admin/degree-types', [
            'name' => 'Tiến sĩ',
            'abbreviation' => 'TS',
            'description' => 'Bằng tiến sĩ',
        ]);

        $response->assertRedirect('/admin/degree-types');
        $this->assertDatabaseHas('degree_types', ['name' => 'Tiến sĩ']);
    }

    public function test_store_validation_fails_with_missing_name(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/admin/degree-types', [
            'name' => '',
            'abbreviation' => 'TS',
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_store_validation_fails_with_duplicate_name(): void
    {
        $user = User::factory()->create();
        DegreeType::factory()->create(['name' => 'Tiến sĩ']);

        $response = $this->actingAs($user)->post('/admin/degree-types', [
            'name' => 'Tiến sĩ',
            'abbreviation' => 'PTS',
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_edit_page_loads(): void
    {
        $user = User::factory()->create();
        $degreeType = DegreeType::factory()->create();

        $response = $this->actingAs($user)->get('/admin/degree-types/' . $degreeType->id . '/edit');

        $response->assertStatus(200);
    }

    public function test_can_update_degree_type(): void
    {
        $user = User::factory()->create();
        $degreeType = DegreeType::factory()->create(['name' => 'Thạc sĩ']);

        $response = $this->actingAs($user)->put('/admin/degree-types/' . $degreeType->id, [
            'name' => 'Thạc sĩ (Updated)',
            'abbreviation' => 'HS',
            'description' => 'Updated description',
        ]);

        $response->assertRedirect('/admin/degree-types');
        $this->assertDatabaseHas('degree_types', ['name' => 'Thạc sĩ (Updated)']);
    }

    public function test_can_delete_degree_type(): void
    {
        $user = User::factory()->create();
        $degreeType = DegreeType::factory()->create();
        $degreeTypeId = $degreeType->id;

        $response = $this->actingAs($user)->delete('/admin/degree-types/' . $degreeTypeId);

        $response->assertRedirect('/admin/degree-types');
        $this->assertDatabaseMissing('degree_types', ['id' => $degreeTypeId]);
    }

    public function test_cannot_delete_degree_type_in_use(): void
    {
        $user = User::factory()->create();
        $degreeType = DegreeType::factory()->create();
        $lecturer = \App\Models\Lecturer::factory()->create();
        \App\Models\AcademicDegree::factory()->create([
            'lecturer_id' => $lecturer->id,
            'degree_type_id' => $degreeType->id,
        ]);

        $response = $this->actingAs($user)->delete('/admin/degree-types/' . $degreeType->id);

        $response->assertRedirect('/admin/degree-types');
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('degree_types', ['id' => $degreeType->id]);
    }
}