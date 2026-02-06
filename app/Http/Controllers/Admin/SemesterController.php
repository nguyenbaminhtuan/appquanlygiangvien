<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use App\Models\AcademicYear; // Import AcademicYear
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule; // Cho rule unique với nhiều cột

class SemesterController extends Controller
{
    public function index(): View
    {
        $semesters = Semester::with('academicYear') // Eager load để hiển thị tên năm học
                             ->orderByDesc('academic_year_id') // Sắp xếp theo năm học mới nhất
                             ->orderBy('name') // Rồi theo tên kì
                             ->paginate(10);
        return view('admin.semesters.index', compact('semesters'));
    }

    public function create(): View
    {
        $academicYears = AcademicYear::orderBy('name', 'desc')->get(); // Lấy danh sách năm học
        return view('admin.semesters.create', compact('academicYears'));
    }

    public function store(Request $request): RedirectResponse
    {
        $academicYear = AcademicYear::findOrFail($request->input('academic_year_id'));

        $validatedData = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('semesters')->where(function ($query) use ($request) {
                    return $query->where('academic_year_id', $request->academic_year_id);
                }),
            ],
            'start_date' => 'required|date|after_or_equal:' . $academicYear->start_date->format('Y-m-d'),
            'end_date' => 'required|date|after_or_equal:start_date|before_or_equal:' . $academicYear->end_date->format('Y-m-d'),
            'is_current' => 'nullable|boolean',
        ]);

        // Xử lý is_current
        if ($request->has('is_current') && $request->boolean('is_current')) {
            // Đặt tất cả các kì khác thành is_current = false
            Semester::where('is_current', true)->update(['is_current' => false]);
            $validatedData['is_current'] = true;
        } else {
            $validatedData['is_current'] = false;
        }

        try {
            Semester::create($validatedData);
            return redirect()->route('semesters.index')->with('success', 'Kì học đã được tạo thành công.');
        } catch (\Exception $e) {
            Log::error("Lỗi khi tạo kì học: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Đã có lỗi xảy ra khi tạo kì học. Ngày bắt đầu/kết thúc của kì phải nằm trong năm học cha.');
        }
    }

    public function edit(Semester $semester): View
    {
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        return view('admin.semesters.edit', compact('semester', 'academicYears'));
    }

    public function update(Request $request, Semester $semester): RedirectResponse
    {
        $academicYear = AcademicYear::findOrFail($request->input('academic_year_id'));

        $validatedData = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('semesters')->where(function ($query) use ($request) {
                    return $query->where('academic_year_id', $request->academic_year_id);
                })->ignore($semester->id), // Bỏ qua bản ghi hiện tại khi check unique
            ],
            'start_date' => 'required|date|after_or_equal:' . $academicYear->start_date->format('Y-m-d'),
            'end_date' => 'required|date|after_or_equal:start_date|before_or_equal:' . $academicYear->end_date->format('Y-m-d'),
            'is_current' => 'nullable|boolean',
        ]);

        // Xử lý is_current
        if ($request->has('is_current') && $request->boolean('is_current')) {
             // Đặt tất cả các kì khác thành is_current = false
            Semester::where('id', '!=', $semester->id)->where('is_current', true)->update(['is_current' => false]);
            $validatedData['is_current'] = true;
        } else {
            // Nếu bỏ check "is_current", đảm bảo nó là false
            // Cần cẩn thận nếu đây là kì duy nhất is_current=true, bạn có thể muốn logic khác
            $validatedData['is_current'] = false;
        }


        try {
            $semester->update($validatedData);
            return redirect()->route('semesters.index')->with('success', 'Kì học đã được cập nhật thành công.');
        } catch (\Exception $e) {
            Log::error("Lỗi khi cập nhật kì học {$semester->id}: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Đã có lỗi xảy ra khi cập nhật kì học. Ngày bắt đầu/kết thúc của kì phải nằm trong năm học cha.');
        }
    }

    public function destroy(Semester $semester): RedirectResponse
    {
        try {
            // Cân nhắc kiểm tra xem có Lớp học phần nào liên kết với kì này không
            if ($semester->scheduledClasses()->exists()) {
                return redirect()->route('semesters.index')
                                 ->with('error', 'Không thể xóa kì học này vì vẫn còn lớp học phần trực thuộc.');
            }
            $semester->delete();
            return redirect()->route('semesters.index')->with('success', 'Kì học đã được xóa thành công.');
        } catch (\Exception $e) {
            Log::error("Lỗi khi xóa kì học {$semester->id}: " . $e->getMessage());
            return redirect()->route('semesters.index')->with('error', 'Đã có lỗi xảy ra khi xóa kì học.');
        }
    }
}