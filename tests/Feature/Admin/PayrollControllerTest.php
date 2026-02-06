<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Semester;
use App\Models\AcademicYear;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PayrollControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        AcademicYear::factory()->count(1)->create();
        Semester::factory()->count(2)->create();
        Setting::factory()->create([
            'key' => 'base_rate_per_teaching_unit',
            'value' => '100000',
        ]);
    }

    public function test_generate_form_loads(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/payroll/generate-form');

        $response->assertStatus(200);
    }

    public function test_calculate_preview_requires_semester(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/admin/payroll/calculate-preview', []);

        $response->assertSessionHasErrors(['semester_id']);
    }

    public function test_calculate_preview_with_valid_semester(): void
    {
        $user = User::factory()->create();
        $semester = Semester::first();

        $response = $this->actingAs($user)->post('/admin/payroll/calculate-preview', [
            'semester_id' => $semester->id,
        ]);

        $response->assertStatus(200);
    }

    public function test_payroll_history_loads(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/payroll/history');

        $response->assertStatus(200);
    }

    public function test_unauthenticated_cannot_access_payroll(): void
    {
        $response = $this->get('/admin/payroll/generate-form');
        $response->assertRedirect('/login');
    }
}