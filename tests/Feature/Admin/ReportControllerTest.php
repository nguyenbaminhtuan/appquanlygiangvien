<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_payroll_report_loads(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/reports/payroll');

        $response->assertStatus(200);
    }

    public function test_subject_class_statistics_loads(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/reports/subject-class-statistics');

        $response->assertStatus(200);
    }

    public function test_reports_require_authentication(): void
    {
        $response = $this->get('/admin/reports/payroll');
        $response->assertRedirect('/login');
    }
}