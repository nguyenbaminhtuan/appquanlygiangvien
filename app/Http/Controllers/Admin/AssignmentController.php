<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScheduledClass;
use App\Models\Semester;
use App\Models\Lecturer;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
class AssignmentController extends Controller
{
    public function index(Request $request): View
    {
        $selectedSemesterId = $request->input('semester_id');
        $query = ScheduledClass::with(['subject', 'lecturer', 'semester.academicYear']);

        $currentSemester = null;
        if ($selectedSemesterId) {
            $currentSemester = Semester::find($selectedSemesterId);
            if ($currentSemester) {
                $query->where('semester_id', $currentSemester->id);
            }
        } else {
            $currentSemester = Semester::where('is_current', true)->first() ?? Semester::orderByDesc('start_date')->first();
            if ($currentSemester) {
                $query->where('semester_id', $currentSemester->id);
                $selectedSemesterId = $currentSemester->id;
            }
        }

        $scheduledClasses = $query->orderBy('subject_id')->orderBy('class_code')->paginate(20);
        $semesters = Semester::with('academicYear')->orderByDesc('start_date')->get();
        $allLecturers = Lecturer::orderBy('full_name')->get();

        // Tính toán tải cho giảng viên (số lớp và tổng tín chỉ) TRONG KÌ ĐANG CHỌN
        $lecturersWithLoad = collect(); // Khởi tạo một collection rỗng
        if ($currentSemester) { // Chỉ tính nếu có kì học được chọn/hiện tại
            $lecturersWithLoad = Lecturer::withCount(['scheduledClasses' => function ($query) use ($currentSemester) {
                $query->where('semester_id', $currentSemester->id);
            }])
            ->withSum(['scheduledClasses as total_credits' => function ($query) use ($currentSemester) {
                $query->where('semester_id', $currentSemester->id)
                      ->join('subjects', 'scheduled_classes.subject_id', '=', 'subjects.id') // Join để lấy số tín chỉ
                      ->select(DB::raw('sum(subjects.credits)')); // Chọn tổng tín chỉ
            }], 'credits') // 'credits' ở đây là alias cho sum(subjects.credits)
            ->orderBy('full_name')
            ->get();

            // Laravel 8+ có thể không tự động thêm total_credits vào model attributes nếu không có select cụ thể.
            // Chúng ta có thể tính lại nếu cần:
            foreach ($lecturersWithLoad as $lecturer) {
                if (!isset($lecturer->total_credits)) { // Nếu withSum không tự thêm thuộc tính
                    $lecturer->total_credits = $lecturer->scheduledClasses()
                        ->where('semester_id', $currentSemester->id)
                        ->join('subjects', 'scheduled_classes.subject_id', '=', 'subjects.id')
                        ->sum('subjects.credits');
                }
                $lecturer->scheduled_classes_count = $lecturer->scheduledClasses()
                                                            ->where('semester_id', $currentSemester->id)
                                                            ->count();
            }

        } else {
            // Nếu không có kì học nào được chọn, tải danh sách giảng viên cơ bản
            $lecturersWithLoad = $allLecturers->map(function ($lecturer) {
                $lecturer->scheduled_classes_count = 0;
                $lecturer->total_credits = 0;
                return $lecturer;
            });
        }


        $subjects = Subject::orderBy('name')->get(); // Vẫn cần cho bộ lọc (nếu có)

        return view('admin.assignments.index', compact(
            'scheduledClasses',
            'semesters',
            'lecturersWithLoad', // Sử dụng biến này thay cho $lecturers
            'subjects',
            'selectedSemesterId',
            'currentSemester' // Truyền kì hiện tại sang view để hiển thị
        ));
    }
    public function assign(Request $request): RedirectResponse
{
    $request->validate([
        'scheduled_class_id' => 'required|exists:scheduled_classes,id',
        'lecturer_id' => 'nullable|exists:lecturers,id', // Cho phép bỏ phân công nếu chọn "-- Chọn Giảng viên --" (value rỗng)
    ]);

    try {
        $scheduledClass = ScheduledClass::findOrFail($request->input('scheduled_class_id'));
        $scheduledClass->lecturer_id = $request->input('lecturer_id'); // Sẽ là null nếu không chọn GV
        $scheduledClass->save();

        return redirect()->back()->with('success', 'Phân công giảng viên cho lớp ' . $scheduledClass->class_code . ' đã được cập nhật.');

    } catch (\Exception $e) {
        Log::error("Lỗi khi phân công giảng viên: " . $e->getMessage());
        return redirect()->back()->with('error', 'Đã có lỗi xảy ra khi cập nhật phân công.');
    }
}
    // Phương thức assign() sẽ được implement sau
}