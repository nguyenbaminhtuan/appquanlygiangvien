<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lecturer;        // Import Lecturer
use App\Models\AcademicDegree;  // Import AcademicDegree
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
// Thêm các use statement cần thiết khác nếu có
use Illuminate\Support\Facades\Log;
use App\Models\DegreeType;

class LecturerAcademicDegreeController extends Controller
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
    // App\Http\Controllers\Admin\LecturerAcademicDegreeController.php
public function create(Lecturer $lecturer): View
{
    $degreeTypes = DegreeType::orderBy('name')->get(); // Lấy danh sách loại bằng cấp
    return view('admin.lecturers.academic-degrees.create', compact('lecturer', 'degreeTypes'));
}

    /**
     * Store a newly created resource in storage.
     */
    
public function store(Request $request, Lecturer $lecturer): RedirectResponse
{
    $validatedData = $request->validate([
        'degree_type_id' => 'required|exists:degree_types,id', // Validation cho degree_type_id
        'specialization' => 'required|string|max:255',      // Validation cho specialization
        'issuing_institution' => 'nullable|string|max:255',
        'date_issued' => 'nullable|date',
        'notes' => 'nullable|string',
    ]);

    try {
        $lecturer->academicDegrees()->create($validatedData); // $validatedData đã chứa degree_type_id

        return redirect()->route('lecturers.edit', $lecturer->id)
                         ->with('success', 'Học vị/Học hàm đã được thêm thành công.');
    } catch (\Exception $e) {
        Log::error("Lỗi khi thêm học vị cho giảng viên {$lecturer->id}: " . $e->getMessage());
        return redirect()->back()
                         ->withInput()
                         ->with('error', 'Đã có lỗi xảy ra khi thêm học vị/học hàm.');
    }
}

    /**
     * Display the specified resource.
     */
    public function show(AcademicDegree $academicDegree)
    {
        //
    }



public function edit(Lecturer $lecturer, AcademicDegree $degree): View
    {
        if ($degree->lecturer_id !== $lecturer->id) {
            abort(404);
        }
        $degreeTypes = DegreeType::orderBy('name')->get(); // Lấy danh sách loại bằng cấp
        // Truyền cả $lecturer, $degree (học vị cần sửa), và $degreeTypes sang view
        return view('admin.lecturers.academic-degrees.edit', compact('lecturer', 'degree', 'degreeTypes'));
    }
    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, Lecturer $lecturer, AcademicDegree $degree): RedirectResponse
{
    if ($degree->lecturer_id !== $lecturer->id) {
        abort(404);
    }

    $validatedData = $request->validate([
        'degree_type_id' => 'required|exists:degree_types,id',
        'specialization' => 'required|string|max:255',
        'issuing_institution' => 'nullable|string|max:255',
        'date_issued' => 'nullable|date',
        'notes' => 'nullable|string',
    ]);

    try {
        $degree->update($validatedData);
        return redirect()->route('lecturers.edit', $lecturer->id)
                         ->with('success', 'Học vị/Học hàm đã được cập nhật thành công.');
    } catch (\Exception $e) {
        Log::error("Lỗi khi cập nhật học vị {$degree->id} cho giảng viên {$lecturer->id}: " . $e->getMessage());
        return redirect()->back()
                         ->withInput()
                         ->with('error', 'Đã có lỗi xảy ra khi cập nhật học vị/học hàm.');
    }
}
   
public function destroy(Lecturer $lecturer, AcademicDegree $degree): RedirectResponse
{
    if ($degree->lecturer_id !== $lecturer->id) {
        abort(404);
    }

    try {
        $degree->delete();
        return redirect()->route('lecturers.edit', $lecturer->id)
                         ->with('success', 'Học vị/Học hàm đã được xóa thành công.');
    } catch (\Exception $e) {
        Log::error("Lỗi khi xóa học vị {$degree->id} cho giảng viên {$lecturer->id}: " . $e->getMessage());
        return redirect()->route('lecturers.edit', $lecturer->id)
                         ->with('error', 'Đã có lỗi xảy ra khi xóa học vị/học hàm.');
    }
}
}
