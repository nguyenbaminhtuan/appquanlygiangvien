<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepartmentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_page_loads(): void
    {
        $user = User::factory()->create();
        Department::factory()->count(3)->create();

        $response = $this->actingAs($user)->get('/admin/departments');

        $response->assertStatus(200);
    }

    public function test_create_page_loads(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/departments/create');

        $response->assertStatus(200);
    }

    public function test_can_store_department(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/admin/departments', [
            'name' => 'Khoa Công nghệ thông tin',
            'code' => 'CNTT',
            'description' => 'Khoa về CNTT',
        ]);

        $response->assertRedirect('/admin/departments');
        $this->assertDatabaseHas('departments', ['code' => 'CNTT']);
    }

    public function test_store_validation_fails(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/admin/departments', [
            'name' => '',
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_can_update_department(): void
    {
        $user = User::factory()->create();
        $department = Department::factory()->create();

        $response = $this->actingAs($user)->put('/admin/departments/' . $department->id, [
            'name' => 'Updated Department',
            'code' => $department->code,
            'description' => 'Updated description',
        ]);

        $response->assertRedirect('/admin/departments');
        $this->assertDatabaseHas('departments', ['name' => 'Updated Department']);
    }

    public function test_can_delete_department(): void
    {
        $user = User::factory()->create();
        $department = Department::factory()->create();
        $deptId = $department->id;

        $response = $this->actingAs($user)->delete('/admin/departments/' . $deptId);

        $response->assertRedirect('/admin/departments');
        $this->assertDatabaseMissing('departments', ['id' => $deptId]);
    }
}