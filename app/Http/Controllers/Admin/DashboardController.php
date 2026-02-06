<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lecturer;
use App\Models\Department;
use App\Models\Subject;
use App\Models\ScheduledClass; // Model Lớp học phần
use App\Models\Semester;       // Model Kì học
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB; // Cần thêm dòng này để dùng hàm DB::raw

class DashboardController extends Controller
{
    public function index(): View
    {
        // --- PHẦN 1: THỐNG KÊ CƠ BẢN (CHO CÁC THẺ CARD) ---
        $totalLecturers = Lecturer::count();
        $totalDepartments = Department::count();
        $totalSubjects = Subject::count();

        // Lấy kì học hiện tại (nếu có)
        $currentSemester = Semester::where('is_current', true)->first();
        $totalScheduledClassesInCurrentSemester = 0;
        
        if ($currentSemester) {
            $totalScheduledClassesInCurrentSemester = ScheduledClass::where('semester_id', $currentSemester->id)->count();
        } else {
            // Nếu không có kì hiện tại, đếm tất cả 
            $totalScheduledClassesInCurrentSemester = ScheduledClass::count();
        }

        $lecturerLevels = Lecturer::select('academic_level', DB::raw('count(*) as total'))
            ->groupBy('academic_level')
            ->get();

        $levelLabels = $lecturerLevels->pluck('academic_level')->toArray(); 
        $levelData = $lecturerLevels->pluck('total')->toArray();            

        $classesByMonth = ScheduledClass::select(
                DB::raw("CAST(strftime('%m', created_at) AS INTEGER) as month"),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('created_at', date('Y')) // Chỉ lấy năm nay
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Tạo mảng chuẩn 12 tháng (mặc định là 0 hết)
        $monthlyData = array_fill(0, 12, 0); 

        // Điền số liệu thực tế vào đúng vị trí tháng
        foreach ($classesByMonth as $item) {
            // $item->month trả về 1-12, nhưng mảng bắt đầu từ 0 nên phải trừ 1
            $monthlyData[$item->month - 1] = $item->total;
        }

        // --- TRẢ VỀ VIEW ---
        return view('dashboard', compact(
            'totalLecturers',
            'totalDepartments',
            'totalSubjects',
            'currentSemester',
            'totalScheduledClassesInCurrentSemester',
            'levelLabels', // Dữ liệu biểu đồ tròn
            'levelData',   // Dữ liệu biểu đồ tròn
            'monthlyData'  // Dữ liệu biểu đồ cột
        ));
    }
}