<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\Lecturer;
use App\Models\ScheduledClass;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException; // Thêm để ném lại lỗi validation nếu cần

class CourseOfferingBatchController extends Controller
{
    /**
     * Show the form for creating a batch of course offerings.
     */
    public function create(): View
    {
        $semesters = Semester::with('academicYear')->orderBy('start_date', 'desc')->get();
        $subjects = Subject::orderBy('name')->get();
        $lecturers = Lecturer::orderBy('full_name')->get(); // Dùng cho dropdown phân công chung

        return view('admin.course-offerings.open-batch-form', compact('semesters', 'subjects', 'lecturers'));
    }

    /**
     * Store a batch of newly created course offerings in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'semester_id' => 'required|exists:semesters,id',
            'subject_id' => 'required|exists:subjects,id',
            'number_of_classes' => 'required|integer|min:1|max:50',
            'max_students_per_class' => 'required|integer|min:1|max:200',
            'default_actual_hours_per_class' => 'nullable|integer|min:0', // <<--- THÊM VALIDATION
            'class_code_prefix' => 'nullable|string|max:50',
            'common_schedule_info' => 'nullable|string',
            'common_lecturer_id' => 'nullable|exists:lecturers,id',
        ]);

        $semester = Semester::findOrFail($validatedData['semester_id']);
        $subject = Subject::findOrFail($validatedData['subject_id']);
        $numberOfClassesToOpen = $validatedData['number_of_classes'];
        $maxStudents = $validatedData['max_students_per_class'];
        // Lấy số tiết thực tế từ form, nếu không có thì lấy số tiết chuẩn từ học phần
        $actualTeachingHours = $request->input('default_actual_hours_per_class', $subject->default_teaching_hours); // <<--- SỬ DỤNG
        $prefix = $request->input('class_code_prefix', $subject->subject_code);
        $commonSchedule = $request->input('common_schedule_info');
        $commonLecturerId = $request->input('common_lecturer_id');

        $createdClassesCount = 0;
        $generatedClassCodes = [];
        $errors = [];

        DB::beginTransaction();
        try {
            $currentMaxSuffixNumber = $this->getCurrentMaxSuffixNumber($semester->id, $subject->id, $prefix);

            for ($i = 0; $i < $numberOfClassesToOpen; $i++) {
                $nextSuffixNumber = $currentMaxSuffixNumber + 1 + $i;
                $classCode = $prefix . '.N' . str_pad($nextSuffixNumber, 2, '0', STR_PAD_LEFT);

                $safetyCounter = 0;
                $tempClassCode = $classCode; // Biến tạm để thử
                $finalSuffixNumber = $nextSuffixNumber;

                while (ScheduledClass::where('semester_id', $semester->id)
                                    ->where('subject_id', $subject->id)
                                    ->where('class_code', $tempClassCode)
                                    ->exists() && $safetyCounter < ($numberOfClassesToOpen + 10)) { // Tăng giới hạn thử
                    Log::warning("Mã lớp {$tempClassCode} dự kiến tạo đã tồn tại, đang thử tìm suffix khác.");
                    $finalSuffixNumber++; // Tăng số thứ tự thực sự sẽ dùng
                    $tempClassCode = $prefix . '.N' . str_pad($finalSuffixNumber, 2, '0', STR_PAD_LEFT);
                    $safetyCounter++;
                }

                if ($safetyCounter >= ($numberOfClassesToOpen + 10) && ScheduledClass::where('semester_id', $semester->id)
                                                                    ->where('subject_id', $subject->id)
                                                                    ->where('class_code', $tempClassCode)
                                                                    ->exists()) {
                    $errors[] = "Không thể tạo mã lớp duy nhất cho lớp thứ " . ($i + 1) . " sau nhiều lần thử (mã cuối thử: {$tempClassCode}). Vui lòng kiểm tra lại hoặc dùng tiền tố khác.";
                    continue;
                }
                // Cập nhật currentMaxSuffixNumber để lần lặp sau của for bắt đầu từ số đúng
                // nếu vòng lặp while ở trên đã tìm được một suffix mới
                $currentMaxSuffixNumber = $finalSuffixNumber - ($i + 1);


                ScheduledClass::create([
                    'semester_id' => $semester->id,
                    'subject_id' => $subject->id,
                    'class_code' => $tempClassCode, // Sử dụng mã lớp đã được đảm bảo duy nhất
                    'max_students' => $maxStudents,
                    'actual_teaching_hours' => $actualTeachingHours, // <<--- SỬ DỤNG GIÁ TRỊ NÀY
                    'actual_students' => 0, // Khởi tạo sĩ số thực tế là 0
                    'schedule_info' => $commonSchedule,
                    'lecturer_id' => $commonLecturerId,
                ]);
                $generatedClassCodes[] = $tempClassCode;
                $createdClassesCount++;
            }

            if (!empty($errors)) {
                DB::rollBack();
                return redirect()->back()
                                 ->withInput()
                                 ->with('error', "Đã có lỗi xảy ra khi tạo mã lớp: " . implode('; ', $errors) . ". Số lớp thực tế đã tạo: 0.");
            }

            DB::commit();

            $successMessage = "Đã mở thành công {$createdClassesCount} lớp cho học phần '{$subject->name}'.";
            if (!empty($generatedClassCodes)) {
                $successMessage .= " Các mã lớp được tạo: " . implode(', ', $generatedClassCodes);
            }

            return redirect()->route('admin.course-offerings.open-batch.create')
                             ->with('success', $successMessage);

        } catch (ValidationException $e) { // Bắt lỗi ValidationException riêng
            DB::rollBack();
            throw $e; // Ném lại để Laravel tự xử lý và hiển thị lỗi trên form
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Lỗi khi mở nhiều lớp học phần: " . $e->getMessage() . " --- Dòng: " . $e->getLine() . " --- File: " . $e->getFile() . " --- Trace: " . $e->getTraceAsString());
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Đã có lỗi nghiêm trọng xảy ra trong quá trình mở lớp. Vui lòng thử lại hoặc liên hệ quản trị viên.');
        }
    }

    private function getCurrentMaxSuffixNumber($semesterId, $subjectId, $prefix): int
    {
        $latestClass = ScheduledClass::where('semester_id', $semesterId)
                                     ->where('subject_id', $subjectId)
                                     ->where('class_code', 'LIKE', $prefix . '.N%')
                                     ->orderByRaw('CAST(SUBSTRING_INDEX(class_code, ".N", -1) AS UNSIGNED) DESC, class_code DESC') // Sắp xếp theo số sau .N
                                     ->first();
        if ($latestClass) {
            if (preg_match('/\.N(\d+)$/', $latestClass->class_code, $matches)) {
                return intval($matches[1]);
            }
        }
        return 0;
    }
}