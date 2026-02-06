<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lecturer;
use App\Models\WorkHistory; // Import WorkHistory
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class LecturerWorkHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Lecturer $lecturer): View
    {
        return view('admin.lecturers.work-histories.create', compact('lecturer'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Lecturer $lecturer): RedirectResponse
    {
        $validatedData = $request->validate([
            'organization_name' => 'required|string|max:255',
            'position_held' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'courses_taught' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        try {
            $lecturer->workHistories()->create($validatedData);

            return redirect()->route('lecturers.edit', $lecturer->id)
                             ->with('success', 'Quá trình công tác đã được thêm thành công.');
        } catch (\Exception $e) {
            Log::error("Lỗi khi thêm quá trình công tác cho giảng viên {$lecturer->id}: " . $e->getMessage());
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Đã có lỗi xảy ra khi thêm quá trình công tác.');
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(WorkHistory $workHistory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lecturer $lecturer, WorkHistory $history): View
{
    if ($history->lecturer_id !== $lecturer->id) {
        abort(404);
    }
    return view('admin.lecturers.work-histories.edit', compact('lecturer', 'history'));
}

    /**
     * Update the specified resource in storage.
     */
    // App\Http\Controllers\Admin\LecturerWorkHistoryController.php
public function update(Request $request, Lecturer $lecturer, WorkHistory $history): RedirectResponse
{
    if ($history->lecturer_id !== $lecturer->id) {
        abort(404);
    }
    $validatedData = $request->validate([
        'organization_name' => 'required|string|max:255',
        'position_held' => 'required|string|max:255',
        'start_date' => 'required|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'courses_taught' => 'nullable|string',
        'description' => 'nullable|string',
    ]);

    try {
        $history->update($validatedData);
        return redirect()->route('lecturers.edit', $lecturer->id)
                         ->with('success', 'Quá trình công tác đã được cập nhật thành công.');
    } catch (\Exception $e) {
        Log::error("Lỗi khi cập nhật quá trình công tác {$history->id} cho giảng viên {$lecturer->id}: " . $e->getMessage());
        return redirect()->back()
                         ->withInput()
                         ->with('error', 'Đã có lỗi xảy ra khi cập nhật quá trình công tác.');
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lecturer $lecturer, WorkHistory $history): RedirectResponse
{
    if ($history->lecturer_id !== $lecturer->id) {
        abort(404);
    }
    try {
        $history->delete();
        return redirect()->route('lecturers.edit', $lecturer->id)
                         ->with('success', 'Quá trình công tác đã được xóa thành công.');
    } catch (\Exception $e) {
        Log::error("Lỗi khi xóa quá trình công tác {$history->id} cho giảng viên {$lecturer->id}: " . $e->getMessage());
        return redirect()->route('lecturers.edit', $lecturer->id)
                         ->with('error', 'Đã có lỗi xảy ra khi xóa quá trình công tác.');
    }
}
}
