<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Lecturer;
use App\Models\WorkHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LecturerWorkHistoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_show_create_form(): void
    {
        $user = User::factory()->create();
        $lecturer = Lecturer::factory()->create();

        $response = $this->actingAs($user)->get('/admin/lecturers/' . $lecturer->id . '/work-histories/create');

        $response->assertStatus(200);
    }

    public function test_can_store_work_history(): void
    {
        $user = User::factory()->create();
        $lecturer = Lecturer::factory()->create();

        $response = $this->actingAs($user)->post('/admin/lecturers/' . $lecturer->id . '/work-histories', [
            'organization_name' => 'Đại học Quốc gia',
            'position_held' => 'Giảng viên',
            'start_date' => '2015-09-01',
            'end_date' => '2020-08-31',
            'courses_taught' => 'Toán cao cấp, Giải tích',
            'description' => 'Giảng dạy các môn toán',
        ]);

        $response->assertRedirect('/admin/lecturers/' . $lecturer->id . '/edit');
        $this->assertDatabaseHas('work_histories', [
            'lecturer_id' => $lecturer->id,
            'organization_name' => 'Đại học Quốc gia',
        ]);
    }

    public function test_store_validation_fails_with_missing_fields(): void
    {
        $user = User::factory()->create();
        $lecturer = Lecturer::factory()->create();

        $response = $this->actingAs($user)->post('/admin/lecturers/' . $lecturer->id . '/work-histories', [
            'organization_name' => '',
            'position_held' => '',
            'start_date' => '',
        ]);

        $response->assertSessionHasErrors(['organization_name', 'position_held', 'start_date']);
    }

    public function test_store_validation_fails_when_end_date_before_start_date(): void
    {
        $user = User::factory()->create();
        $lecturer = Lecturer::factory()->create();

        $response = $this->actingAs($user)->post('/admin/lecturers/' . $lecturer->id . '/work-histories', [
            'organization_name' => 'Test Org',
            'position_held' => 'Test Position',
            'start_date' => '2020-01-01',
            'end_date' => '2019-01-01',
        ]);

        $response->assertSessionHasErrors(['end_date']);
    }

    public function test_can_show_edit_form(): void
    {
        $user = User::factory()->create();
        $lecturer = Lecturer::factory()->create();
        $workHistory = WorkHistory::factory()->create(['lecturer_id' => $lecturer->id]);

        $response = $this->actingAs($user)->get('/admin/lecturers/' . $lecturer->id . '/work-histories/' . $workHistory->id . '/edit');

        $response->assertStatus(200);
    }

    public function test_can_update_work_history(): void
    {
        $user = User::factory()->create();
        $lecturer = Lecturer::factory()->create();
        $workHistory = WorkHistory::factory()->create([
            'lecturer_id' => $lecturer->id,
            'organization_name' => 'Old Org',
        ]);

        $response = $this->actingAs($user)->put('/admin/lecturers/' . $lecturer->id . '/work-histories/' . $workHistory->id, [
            'organization_name' => 'New Organization',
            'position_held' => 'Senior Lecturer',
            'start_date' => '2018-01-01',
            'end_date' => '2023-12-31',
        ]);

        $response->assertRedirect('/admin/lecturers/' . $lecturer->id . '/edit');
        $this->assertDatabaseHas('work_histories', ['organization_name' => 'New Organization']);
    }

    public function test_can_delete_work_history(): void
    {
        $user = User::factory()->create();
        $lecturer = Lecturer::factory()->create();
        $workHistory = WorkHistory::factory()->create(['lecturer_id' => $lecturer->id]);
        $historyId = $workHistory->id;

        $response = $this->actingAs($user)->delete('/admin/lecturers/' . $lecturer->id . '/work-histories/' . $historyId);

        $response->assertRedirect('/admin/lecturers/' . $lecturer->id . '/edit');
        $this->assertDatabaseMissing('work_histories', ['id' => $historyId]);
    }

    public function test_cannot_edit_history_from_other_lecturer(): void
    {
        $user = User::factory()->create();
        $lecturer1 = Lecturer::factory()->create();
        $lecturer2 = Lecturer::factory()->create();
        $workHistory = WorkHistory::factory()->create(['lecturer_id' => $lecturer1->id]);

        $response = $this->actingAs($user)->get('/admin/lecturers/' . $lecturer2->id . '/work-histories/' . $workHistory->id . '/edit');

        $response->assertStatus(404);
    }
}