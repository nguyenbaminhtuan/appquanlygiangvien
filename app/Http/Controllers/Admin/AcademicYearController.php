<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class AcademicYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(): View
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->paginate(10);
        return view('admin.academic-years.index', compact('academicYears'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.academic-years.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:academic_years,name',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        try {
            AcademicYear::create($validatedData);
            return redirect()->route('academic-years.index')->with('success', 'Năm học đã được tạo thành công.');
        } catch (\Exception $e) {
            Log::error("Lỗi khi tạo năm học: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Đã có lỗi xảy ra khi tạo năm học.');
        }
    }

    // Phương thức show() thường không cần thiết cho quản lý danh mục đơn giản này.
    // Nếu cần, bạn có thể implement nó để xem chi tiết, hoặc redirect về edit.
    // public function show(AcademicYear $academicYear)
    // {
    //     // return view('admin.academic-years.show', compact('academicYear'));
    //     return redirect()->route('academic-years.edit', $academicYear);
    // }

    /**
     * Display the specified resource.
     */
    public function show(AcademicYear $academicYear)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AcademicYear $academicYear): View // Route Model Binding
    {
        return view('admin.academic-years.edit', compact('academicYear'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AcademicYear $academicYear): RedirectResponse // Route Model Binding
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:academic_years,name,' . $academicYear->id,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        try {
            $academicYear->update($validatedData);
            return redirect()->route('academic-years.index')->with('success', 'Năm học đã được cập nhật thành công.');
        } catch (\Exception $e) {
            Log::error("Lỗi khi cập nhật năm học {$academicYear->id}: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Đã có lỗi xảy ra khi cập nhật năm học.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicYear $academicYear): RedirectResponse // Route Model Binding
    {
        try {
            // Kiểm tra xem có Kì học (Semester) nào liên kết với năm học này không
            // (Cần có relationship semesters() trong model AcademicYear)
            if ($academicYear->semesters()->exists()) { // Hoặc ->count() > 0
                return redirect()->route('academic-years.index')
                                 ->with('error', 'Không thể xóa năm học này vì vẫn còn các kì học trực thuộc. Vui lòng xóa các kì học trước.');
            }

            $academicYear->delete();
            return redirect()->route('academic-years.index')->with('success', 'Năm học đã được xóa thành công.');
        } catch (\Exception $e) {
            Log::error("Lỗi khi xóa năm học {$academicYear->id}: " . $e->getMessage());
            return redirect()->route('academic-years.index')->with('error', 'Đã có lỗi xảy ra khi xóa năm học.');
        }
    }
}
