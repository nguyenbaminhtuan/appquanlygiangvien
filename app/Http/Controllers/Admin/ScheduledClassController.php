<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScheduledClass;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\Lecturer; // <<--- THÊM DÒNG NÀY
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse; // <<--- THÊM DÒNG NÀY
use Illuminate\Support\Facades\Log;    // <<--- THÊM DÒNG NÀY
use Illuminate\Validation\Rule; 
class ScheduledClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = ScheduledClass::with(['semester.academicYear', 'subject', 'lecturer']);

        // Lọc theo Kì học
        if ($request->filled('filter_semester_id')) {
            $query->where('semester_id', $request->input('filter_semester_id'));
        }

        // Lọc theo Học phần
        if ($request->filled('filter_subject_id')) {
            $query->where('subject_id', $request->input('filter_subject_id'));
        }

        // Lọc theo Mã lớp hoặc Tên học phần (nếu cần tìm kiếm chung)
        if ($request->filled('search_term')) {
            $searchTerm = $request->input('search_term');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('class_code', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('subject', function ($subQuery) use ($searchTerm) {
                      $subQuery->where('name', 'LIKE', "%{$searchTerm}%")
                               ->orWhere('subject_code', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }

        $scheduledClasses = $query->orderByDesc('semester_id') // Sắp xếp theo kì mới nhất
                                   ->orderBy('subject_id')      // Rồi theo học phần
                                   ->orderBy('class_code')      // Rồi theo mã lớp
                                   ->paginate(15); // Ví dụ 15 lớp/trang

        $semesters = Semester::with('academicYear')->orderByDesc('start_date')->get(); // Để lọc
        $subjects = Subject::orderBy('name')->get(); // Để lọc

        return view('admin.scheduled-classes.index', compact('scheduledClasses', 'semesters', 'subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ScheduledClass $scheduledClass)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
   public function edit(ScheduledClass $scheduledClass): View // Route Model Binding
    {
        // Eager load các relationship để hiển thị thông tin chi tiết hơn (tùy chọn)
        $scheduledClass->load(['semester.academicYear', 'subject', 'lecturer']);

        $semesters = Semester::with('academicYear')->orderByDesc('start_date')->get();
        $subjects = Subject::orderBy('name')->get();
        $lecturers = Lecturer::orderBy('full_name')->get();

        return view('admin.scheduled-classes.edit', compact('scheduledClass', 'semesters', 'subjects', 'lecturers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ScheduledClass $scheduledClass): RedirectResponse
{
    $validatedData = $request->validate([
        'semester_id' => 'required|exists:semesters,id',
        'subject_id' => 'required|exists:subjects,id',
        'actual_teaching_hours' => 'nullable|integer|min:0',
        'actual_students' => 'nullable|integer|min:0',
        'class_code' => [
            'required',
            'string',
            'max:255',
            Rule::unique('scheduled_classes')->where(function ($query) use ($request) {
                return $query->where('semester_id', $request->semester_id)
                             ->where('subject_id', $request->subject_id);
            })->ignore($scheduledClass->id), // Bỏ qua bản ghi hiện tại khi check unique
        ],
        'max_students' => 'required|integer|min:1|max:200',
        'schedule_info' => 'nullable|string',
        'lecturer_id' => 'nullable|exists:lecturers,id',
        'notes' => 'nullable|string',
        // Không cho phép sửa 'current_students' trực tiếp từ form này
    ]);

    try {
        $scheduledClass->update($validatedData);
        return redirect()->route('scheduled-classes.index')
                         ->with('success', 'Thông tin Lớp học phần đã được cập nhật thành công.');
    } catch (\Exception $e) {
        Log::error("Lỗi khi cập nhật Lớp học phần {$scheduledClass->id}: " . $e->getMessage());
        return redirect()->back()
                         ->withInput()
                         ->with('error', 'Đã có lỗi xảy ra khi cập nhật thông tin: ' . $e->getMessage());
    }
}

    /**
     * Remove the specified resource from storage.
     */
     public function destroy(ScheduledClass $scheduledClass): RedirectResponse // Route Model Binding
    {
        try {
            // Cân nhắc kiểm tra các ràng buộc trước khi xóa, ví dụ:
            // if ($scheduledClass->current_students > 0) {
            //     return redirect()->route('scheduled-classes.index')
            //                      ->with('error', 'Không thể xóa lớp học phần này vì đã có sinh viên đăng ký.');
            // }

            $className = $scheduledClass->class_code; // Lấy tên/mã lớp để hiển thị thông báo
            $scheduledClass->delete();

            return redirect()->route('scheduled-classes.index')
                             ->with('success', "Lớp học phần '{$className}' đã được xóa thành công.");

        } catch (\Exception $e) {
            Log::error("Lỗi khi xóa Lớp học phần {$scheduledClass->id}: " . $e->getMessage());
            return redirect()->route('scheduled-classes.index')
                             ->with('error', 'Đã có lỗi xảy ra khi xóa Lớp học phần. Vui lòng thử lại.');
        }
    }
}
