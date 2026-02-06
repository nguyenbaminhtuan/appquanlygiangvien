<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;         // Dùng trong eager load Semester
use App\Models\ClassSizeCoefficient;  // Để lấy hệ số sĩ số lớp
use App\Models\Lecturer;              // Để lấy thông tin giảng viên (ví dụ: academic_level)
use App\Models\LecturerClassPayment;  // Để lưu kết quả tính toán
use App\Models\LecturerPayRate;       // Để lấy hệ số lương giảng viên
use App\Models\ScheduledClass;        // Để lấy danh sách lớp học phần
use App\Models\Semester;              // Để chọn kì học và eager load
use App\Models\Setting;               // Để lấy base_rate_per_teaching_unit
use App\Models\Subject;               // Để lấy thông tin học phần (tín chỉ, hệ số HP, số tiết chuẩn)
use Illuminate\Http\Request;          // Để nhận request từ form
use Illuminate\Http\RedirectResponse; // Để khai báo kiểu trả về khi redirect
use Illuminate\Support\Facades\DB;    // Để sử dụng DB Transaction
use Illuminate\Support\Facades\Log;   // Để ghi log lỗi
use Illuminate\View\View;             // Để khai báo kiểu trả về cho các phương thức trả về view
use Illuminate\Validation\ValidationException;

class PayrollController extends Controller
{
    public function showGenerateForm(): View
    {
        $semesters = Semester::with('academicYear')->orderByDesc('start_date')->get();
        return view('admin.payroll.generate-form', compact('semesters'));
    }

    // calculateAndPreview và processAndSavePayroll sẽ được implement sau
    public function calculateAndPreview(Request $request): View // Hoặc trả về RedirectResponse nếu có lỗi ngay
{
    $request->validate(['semester_id' => 'required|exists:semesters,id']);
    $selectedSemesterId = $request->input('semester_id');
    $selectedSemester = Semester::with('academicYear')->findOrFail($selectedSemesterId);

    // Lấy tiền dạy một tiết chuẩn
    $baseRateSetting = Setting::where('key', 'base_rate_per_teaching_unit')->first();
    $baseRatePerTeachingUnit = $baseRateSetting ? (float) $baseRateSetting->value : 0; // Mặc định là 0 nếu không có setting

    if ($baseRatePerTeachingUnit == 0) {
        // Có thể redirect với lỗi hoặc hiển thị thông báo
        return view('admin.payroll.generate-form', [
            'semesters' => Semester::with('academicYear')->orderByDesc('start_date')->get(),
            'selectedSemester' => $selectedSemester,
            'payrollData' => collect(), // Dữ liệu trống
            'errorMessage' => 'Chưa cấu hình Tiền dạy một tiết chuẩn (base_rate_per_teaching_unit) trong Settings.'
        ]);
    }

    // Lấy tất cả các lớp học phần trong kì đã chọn VÀ ĐÃ ĐƯỢC PHÂN CÔNG GIẢNG VIÊN
    $classesInSemester = ScheduledClass::with(['subject', 'lecturer.department', 'lecturer']) // Eager load
                                       ->where('semester_id', $selectedSemesterId)
                                       ->whereNotNull('lecturer_id') // Chỉ tính các lớp đã có giảng viên
                                       ->get();
    $payrollData = collect();
    $lecturerPayRates = LecturerPayRate::all()->keyBy('academic_level_or_title'); // Lấy và key theo trình độ
    $classSizeCoefficients = ClassSizeCoefficient::all();

    foreach ($classesInSemester as $class) {
        if (!$class->lecturer || !$class->subject) continue; // Bỏ qua nếu thiếu thông tin cơ bản

        $actualTeachingHours = $class->actual_teaching_hours ?? $class->subject->default_teaching_hours ?? 0;
        $actualStudents = $class->actual_students ?? 0; // Nếu null thì coi như 0

        // 1. Xác định Hệ số Học phần
        $subjectCoefficient = $class->subject->subject_coefficient ?? 1.0;

        // 2. Xác định Hệ số Lớp
        $classSizeCoefficientValue = 0; // Mặc định
        foreach ($classSizeCoefficients as $csc) {
            if ($actualStudents >= $csc->min_students && (is_null($csc->max_students) || $actualStudents <= $csc->max_students)) {
                $classSizeCoefficientValue = $csc->coefficient;
                break;
            }
        }

        // 3. Tính Số tiết quy đổi
        $convertedTeachingUnits = $actualTeachingHours * ($subjectCoefficient + $classSizeCoefficientValue);

        // 4. Xác định Hệ số Giáo viên
        $lecturerCoefficientValue = 1.0; // Mặc định
        // Giả sử 'academic_level' trong 'lecturers' khớp với 'academic_level_or_title' trong 'lecturer_pay_rates'
        $lecturerAcademicLevel = $class->lecturer->academic_level;
        if ($lecturerPayRates->has($lecturerAcademicLevel)) {
            $lecturerCoefficientValue = $lecturerPayRates->get($lecturerAcademicLevel)->coefficient;
        } else {
            Log::warning("Không tìm thấy hệ số lương cho trình độ/chức danh: {$lecturerAcademicLevel} của giảng viên {$class->lecturer->full_name}");
        }

        // 5. Tính Tiền dạy mỗi lớp
        $paymentAmount = $convertedTeachingUnits * $lecturerCoefficientValue * $baseRatePerTeachingUnit;

        $payrollData->push([
            'lecturer_name' => $class->lecturer->full_name,
            'lecturer_id' => $class->lecturer->id,
            'scheduled_class_id' => $class->id,
            'class_code' => $class->class_code,
            'subject_name' => $class->subject->name,
            'subject_id' => $class->subject->id,
            'actual_teaching_hours' => $actualTeachingHours,
            'subject_coefficient' => (float) $subjectCoefficient,
            'actual_students' => $actualStudents,
            'class_size_coefficient' => (float) $classSizeCoefficientValue,
            'converted_teaching_units' => (float) $convertedTeachingUnits,
            'lecturer_coefficient' => (float) $lecturerCoefficientValue,
            'base_rate' => (float) $baseRatePerTeachingUnit,
            'payment_amount' => round((float) $paymentAmount), // Làm tròn
            // Lưu các snapshot để nếu sau này hệ số thay đổi thì dữ liệu cũ không bị ảnh hưởng
            'actual_teaching_hours_snapshot' => $actualTeachingHours,
            'subject_coefficient_snapshot' => (float) $subjectCoefficient,
            'class_size_coefficient_snapshot' => (float) $classSizeCoefficientValue,
            'lecturer_coefficient_snapshot' => (float) $lecturerCoefficientValue,
            'base_rate_snapshot' => (float) $baseRatePerTeachingUnit,
        ]);
    }

    return view('admin.payroll.generate-form', [
        'semesters' => Semester::with('academicYear')->orderByDesc('start_date')->get(),
        'selectedSemester' => $selectedSemester,
        'payrollData' => $payrollData,
        'baseRatePerTeachingUnit' => $baseRatePerTeachingUnit, // Truyền thêm đơn giá
    ]);
    }
    public function processAndSavePayroll(Request $request): RedirectResponse
{
    $request->validate([
        'semester_id_to_process' => 'required|exists:semesters,id',
    ]);

    $semesterIdToProcess = $request->input('semester_id_to_process');
    $selectedSemester = Semester::with('academicYear')->findOrFail($semesterIdToProcess);

    // Lấy lại logic tính toán từ calculateAndPreview để đảm bảo dữ liệu là mới nhất
    // Hoặc bạn có thể truyền dữ liệu $payrollData đã tính từ form trước đó qua input hidden (ít an toàn hơn nếu dữ liệu lớn)

    // ----- Bắt đầu đoạn code tính toán (tương tự như trong calculateAndPreview) -----
    $baseRateSetting = Setting::where('key', 'base_rate_per_teaching_unit')->first();
    $baseRatePerTeachingUnit = $baseRateSetting ? (float) $baseRateSetting->value : 0;

    if ($baseRatePerTeachingUnit == 0) {
        return redirect()->route('admin.payroll.generate-form')->with('error', 'Chưa cấu hình Tiền dạy một tiết chuẩn.');
    }

    $classesInSemester = ScheduledClass::with(['subject', 'lecturer'])
                                       ->where('semester_id', $selectedSemester->id)
                                       ->whereNotNull('lecturer_id')
                                       ->get();
    $lecturerPayRates = LecturerPayRate::all()->keyBy('academic_level_or_title');
    $classSizeCoefficients = ClassSizeCoefficient::all();
    $paymentsToInsert = [];
    $calculationDate = now();
    // ----- Kết thúc đoạn code chuẩn bị dữ liệu chung -----

    DB::beginTransaction();
    try {
        // Xóa các bản ghi thanh toán cũ của kỳ này (nếu có) để tránh trùng lặp khi tính lại
        LecturerClassPayment::where('semester_id', $selectedSemester->id)->delete();

        foreach ($classesInSemester as $class) {
            if (!$class->lecturer || !$class->subject) continue;

            $actualTeachingHours = $class->actual_teaching_hours ?? $class->subject->default_teaching_hours ?? 0;
            $actualStudents = $class->actual_students ?? 0;
            $subjectCoefficient = $class->subject->subject_coefficient ?? 1.0;
            $classSizeCoefficientValue = 0;
            foreach ($classSizeCoefficients as $csc) {
                if ($actualStudents >= $csc->min_students && (is_null($csc->max_students) || $actualStudents <= $csc->max_students)) {
                    $classSizeCoefficientValue = $csc->coefficient;
                    break;
                }
            }
            $convertedTeachingUnits = $actualTeachingHours * ($subjectCoefficient + $classSizeCoefficientValue);
            $lecturerCoefficientValue = 1.0;
            $lecturerAcademicLevel = $class->lecturer->academic_level;
            if ($lecturerPayRates->has($lecturerAcademicLevel)) {
                $lecturerCoefficientValue = $lecturerPayRates->get($lecturerAcademicLevel)->coefficient;
            }

            $paymentAmount = $convertedTeachingUnits * $lecturerCoefficientValue * $baseRatePerTeachingUnit;

            $paymentsToInsert[] = [
                'lecturer_id' => $class->lecturer->id,
                'scheduled_class_id' => $class->id,
                'semester_id' => $selectedSemester->id,
                'academic_year_id' => $selectedSemester->academic_year_id,
                'actual_teaching_hours_snapshot' => $actualTeachingHours,
                'subject_coefficient_snapshot' => (float) $subjectCoefficient,
                'class_size_coefficient_snapshot' => (float) $classSizeCoefficientValue,
                'lecturer_coefficient_snapshot' => (float) $lecturerCoefficientValue,
                'base_rate_snapshot' => (float) $baseRatePerTeachingUnit,
                'converted_teaching_units' => round((float) $convertedTeachingUnits, 2),
                'payment_amount' => round((float) $paymentAmount),
                'calculation_date' => $calculationDate,
                'status' => 'calculated', // Trạng thái ban đầu
                'created_at' => $calculationDate,
                'updated_at' => $calculationDate,
            ];
        }

        if (!empty($paymentsToInsert)) {
            LecturerClassPayment::insert($paymentsToInsert); // Insert hàng loạt
        }

        DB::commit();
        return redirect()->route('admin.payroll.generate-form')->with('success', 'Bảng lương cho kì ' . $selectedSemester->name . ' đã được tính và lưu thành công.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Lỗi khi xử lý và lưu bảng lương: " . $e->getMessage() . " Dòng: " . $e->getLine());
        return redirect()->route('admin.payroll.generate-form')->with('error', 'Đã có lỗi xảy ra khi xử lý bảng lương: ' . $e->getMessage());
    }
}

    public function history(Request $request): View
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        $selectedAcademicYearId = $request->input('academic_year_id');

        $query = LecturerClassPayment::query()
            ->select(
                'semester_id',
                DB::raw('COUNT(DISTINCT lecturer_id) as total_lecturers'),
                DB::raw('SUM(payment_amount) as total_payment'),
                DB::raw('MAX(calculation_date) as last_calculation_date')
            )
            ->with('semester.academicYear') // Eager load để lấy thông tin kì và năm học
            ->groupBy('semester_id');

        if ($selectedAcademicYearId) {
            $query->where('academic_year_id', $selectedAcademicYearId);
        }

        $payrollSummaries = $query->orderByDesc('last_calculation_date')->paginate(10);

        return view('admin.payroll.history', compact('payrollSummaries', 'academicYears', 'selectedAcademicYearId'));
    }

    /**
     * Display the detailed payroll for a specific semester.
     */
    public function showHistoryDetail(Semester $semester): View
    {
        $payrollDetails = LecturerClassPayment::with(['lecturer', 'scheduledClass.subject'])
            ->where('semester_id', $semester->id)
            ->orderBy('lecturer_id') // Sắp xếp theo giảng viên
            ->get();

        $grandTotal = $payrollDetails->sum('payment_amount');

        return view('admin.payroll.history-detail', compact('payrollDetails', 'semester', 'grandTotal'));
    }

    /**
     * (Ví dụ) Update the payment status for a payment record.
     */
    public function updatePaymentStatus(Request $request): RedirectResponse
    {
        $request->validate([
            'payment_id' => 'required|exists:lecturer_class_payments,id',
            'status' => 'required|string|in:paid,rejected,pending', // Các trạng thái hợp lệ
        ]);

        try {
            $payment = LecturerClassPayment::findOrFail($request->input('payment_id'));
            $payment->status = $request->input('status');
            $payment->save();
            return redirect()->back()->with('success', 'Cập nhật trạng thái thanh toán thành công.');
        } catch (\Exception $e) {
            Log::error("Lỗi cập nhật trạng thái thanh toán: " . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi cập nhật trạng thái.');
        }
    }
}