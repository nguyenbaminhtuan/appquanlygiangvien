<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController; // THÊM DÒNG NÀY
use App\Http\Controllers\Admin\LecturerController;
use App\Http\Controllers\Admin\LecturerAcademicDegreeController; // Thêm
use App\Http\Controllers\Admin\LecturerWorkHistoryController;
use App\Http\Controllers\Admin\DegreeTypeController;
use App\Http\Controllers\Admin\AcademicYearController;
use App\Http\Controllers\Admin\SemesterController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\CourseOfferingBatchController;
use App\Http\Controllers\Admin\ScheduledClassController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AssignmentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\LecturerPayRateController;
use App\Http\Controllers\Admin\ClassSizeCoefficientController;
use App\Http\Controllers\Admin\PayrollController;

Route::get('/', function () {
    return view('welcome');
});

// Routes cho Đăng ký
Route::get('/register', [RegisteredUserController::class, 'create'])->middleware('guest')->name('register');
Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('guest');

// Routes cho Đăng nhập
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->middleware('guest')->name('login'); // ĐẶT TÊN ROUTE LÀ 'login'
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('guest');

// Route cho Đăng xuất
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');




// Route cho Dashboard (yêu cầu đăng nhập)
Route::get('/dashboard', [DashboardController::class, 'index']) // Sửa thành dòng này
    ->middleware(['auth'])->name('dashboard');


// Các route quản lý khác đã có (yêu cầu đăng nhập)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('admin/payroll/generate-form', [PayrollController::class, 'showGenerateForm'])->name('admin.payroll.generate-form');
    Route::post('admin/payroll/calculate-preview', [PayrollController::class, 'calculateAndPreview'])->name('admin.payroll.calculate-preview'); // Để xem trước
    Route::post('admin/payroll/process-and-save', [PayrollController::class, 'processAndSavePayroll'])->name('admin.payroll.process-and-save'); // Để chốt và lưu

    Route::get('admin/payroll/history', [PayrollController::class, 'history'])->name('admin.payroll.history');
    Route::get('admin/payroll/history/{semester}', [PayrollController::class, 'showHistoryDetail'])->name('admin.payroll.history.show');
    Route::post('admin/payroll/update-payment-status', [PayrollController::class, 'updatePaymentStatus'])->name('admin.payroll.update-payment-status'); // Để cập nhật trạng thái

    Route::get('admin/reports/payroll', [ReportController::class, 'payrollReport'])->name('admin.reports.payroll');
    Route::get('admin/reports/subject-class-statistics', [ReportController::class, 'subjectClassStatistics'])->name('admin.reports.subject-class-statistics');

    Route::resource('admin/departments', DepartmentController::class);
    Route::resource('admin/lecturers', LecturerController::class);
    Route::resource('admin/degree-types', DegreeTypeController::class)
        ->parameters(['degree-types' => 'degreeType']);
    
    Route::resource('admin/academic-years', AcademicYearController::class)
        ->parameters(['academic-years' => 'academicYear']);

     Route::resource('admin/semesters', SemesterController::class)
        ->parameters(['semesters' => 'semester']);

     Route::resource('admin/subjects', SubjectController::class)
        ->parameters(['subjects' => 'subject']);

    Route::resource('admin/class-size-coefficients', ClassSizeCoefficientController::class)
        ->parameters(['class-size-coefficients' => 'classSizeCoefficient']); // Để route model binding nhận đúng tên biến

    Route::resource('admin/lecturer-pay-rates', LecturerPayRateController::class)
        ->parameters(['lecturer-pay-rates' => 'lecturerPayRate']); // Để route model binding nhận đúng tên biến

    Route::get('admin/reports/subject-class-statistics', [ReportController::class, 'subjectClassStatistics'])
         ->name('admin.reports.subject-class-statistics');
    
    Route::get('admin/assignments', [AssignmentController::class, 'index'])->name('admin.assignments.index');
    Route::post('admin/assignments/assign', [AssignmentController::class, 'assign'])->name('admin.assignments.assign'); // Route để xử lý việc gán GV

     Route::get('admin/course-offerings/open-batch', [CourseOfferingBatchController::class, 'create'])->name('admin.course-offerings.open-batch.create');
    Route::post('admin/course-offerings/open-batch', [CourseOfferingBatchController::class, 'store'])->name('admin.course-offerings.open-batch.store');

     Route::resource('admin/scheduled-classes', ScheduledClassController::class)
        ->parameters(['scheduled-classes' => 'scheduledClass']); // Để route model binding nhận đúng tên biến $scheduledClass

    // === QUẢN LÝ HỌC VỊ CỦA GIẢNG VIÊN ===
    Route::prefix('admin/lecturers/{lecturer}/academic-degrees')->name('admin.lecturers.academic-degrees.')->group(function () {
        Route::get('/create', [LecturerAcademicDegreeController::class, 'create'])->name('create');
        Route::post('/', [LecturerAcademicDegreeController::class, 'store'])->name('store');
        Route::get('/{degree}/edit', [LecturerAcademicDegreeController::class, 'edit'])->name('edit');
        Route::put('/{degree}', [LecturerAcademicDegreeController::class, 'update'])->name('update');
        Route::delete('/{degree}', [LecturerAcademicDegreeController::class, 'destroy'])->name('destroy');
    });

    // === QUẢN LÝ QUÁ TRÌNH CÔNG TÁC CỦA GIẢNG VIÊN ===
    Route::prefix('admin/lecturers/{lecturer}/work-histories')->name('admin.lecturers.work-histories.')->group(function () {
        Route::get('/create', [LecturerWorkHistoryController::class, 'create'])->name('create');
        Route::post('/', [LecturerWorkHistoryController::class, 'store'])->name('store');
        Route::get('/{history}/edit', [LecturerWorkHistoryController::class, 'edit'])->name('edit');
        Route::put('/{history}', [LecturerWorkHistoryController::class, 'update'])->name('update');
        Route::delete('/{history}', [LecturerWorkHistoryController::class, 'destroy'])->name('destroy');
    });
});