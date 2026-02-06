<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Lecturer;
use App\Models\Department;
use App\Models\Subject;
use App\Models\Semester;
use App\Models\AcademicYear;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create related records in correct order (AcademicYear before Semester)
        Department::factory()->count(3)->create();
        AcademicYear::factory()->count(2)->create();
        Semester::factory()->count(2)->create();
        Lecturer::factory()->count(5)->create();
        Subject::factory()->count(4)->create();
    }

    public function test_dashboard_loads_for_authenticated_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Bảng điều khiển');
    }

    public function test_dashboard_shows_statistics(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $this->assertDatabaseCount('lecturers', 5);
        $this->assertDatabaseCount('departments', 3);
        $this->assertDatabaseCount('subjects', 4);
    }

    public function test_dashboard_redirects_guest(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }
}