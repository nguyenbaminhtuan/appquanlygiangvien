<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lecturer;
use App\Models\Department;
//use App\Models\AcademicDegree; // Thêm model
//use App\Models\WorkHistory;   
use App\Models\DegreeType; 
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Log;

class LecturerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View // Inject Request
    {
        // Bắt đầu query
        $query = Lecturer::with('department');

        // Lọc theo tên hoặc mã giảng viên
        if ($request->filled('search_term')) {
            $searchTerm = $request->input('search_term');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('full_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('lecturer_code', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Lọc theo Khoa/Bộ môn
        if ($request->filled('search_department')) {
            $query->where('department_id', $request->input('search_department'));
        }

        // Lọc theo Trình độ học vấn
        if ($request->filled('search_level')) {
            $query->where('academic_level', $request->input('search_level'));
        }

        // Sắp xếp và phân trang
        $lecturers = $query->orderBy('full_name', 'asc')->paginate(10);

        // Lấy danh sách các khoa để hiển thị trong dropdown lọc
        $departmentsForSearch = Department::orderBy('name', 'asc')->get();

        return view('admin.lecturers.index', compact('lecturers', 'departmentsForSearch'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $departments = Department::orderBy('name', 'asc')->get();
        $degreeTypes = DegreeType::orderBy('name')->get(); // <<--- THÊM DÒNG NÀY
        return view('admin.lecturers.create', compact('departments', 'degreeTypes')); // <<--- THÊM $degreeTypes VÀO COMPACT
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            // Validation cho thông tin cơ bản của giảng viên
            'lecturer_code' => 'required|string|max:50|unique:lecturers,lecturer_code',
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|string|in:Nam,Nữ,Khác',
            'email' => 'required|string|email|max:255|unique:lecturers,email',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'academic_level' => 'required|string|max:100', // Trình độ học vấn hiện tại (cao nhất)
            'position' => 'nullable|string|max:100',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Thêm webp nếu cần

            // Validation cho mảng academic_degrees (cho một bản ghi)
            'academic_degrees' => 'nullable|array', // Cho phép toàn bộ mục học vị có thể không được gửi
            // Các trường con chỉ bắt buộc nếu academic_degrees được gửi và có giá trị
            'academic_degrees.*.degree_type_id' => 'required_with:academic_degrees.*.specialization|nullable|exists:degree_types,id',
            'academic_degrees.*.specialization' => 'required_with:academic_degrees.*.degree_type_id|nullable|string|max:255',
            'academic_degrees.*.issuing_institution' => 'nullable|string|max:255',
            'academic_degrees.*.date_issued' => 'nullable|date',
            'academic_degrees.*.notes' => 'nullable|string',

            // Validation cho mảng work_histories (cho một bản ghi)
            'work_histories' => 'nullable|array',
            'work_histories.*.organization_name' => 'required_with:work_histories.*.position_held,work_histories.*.start_date|nullable|string|max:255',
            'work_histories.*.position_held' => 'nullable|string|max:255',
            'work_histories.*.start_date' => 'nullable|date',
            'work_histories.*.end_date' => 'nullable|date|after_or_equal:work_histories.*.start_date',
            'work_histories.*.courses_taught' => 'nullable|string',
            'work_histories.*.description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $avatarPath = null;
            if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
                $avatarFile = $request->file('avatar');
                $avatarName = time() . '_' . Str::slug(pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $avatarFile->getClientOriginalExtension();
                $avatarPath = $avatarFile->storeAs('avatars', $avatarName, 'public');
            }

            // Tạo giảng viên trước
            $lecturer = Lecturer::create([
                'lecturer_code' => $validatedData['lecturer_code'],
                'full_name' => $validatedData['full_name'],
                'date_of_birth' => $validatedData['date_of_birth'],
                'gender' => $validatedData['gender'],
                'email' => $validatedData['email'],
                'phone_number' => $request->input('phone_number'), // Lấy trực tiếp từ request cho các trường nullable
                'address' => $request->input('address'),
                'department_id' => $request->input('department_id'),
                'academic_level' => $validatedData['academic_level'],
                'position' => $request->input('position'),
                'avatar' => $avatarPath,
            ]);

            // Lưu thông tin học vị/học hàm (nếu có và hợp lệ)
            // Kiểm tra xem mảng academic_degrees có tồn tại và phần tử đầu tiên có degree_type_id không
            if ($request->filled('academic_degrees.0.degree_type_id') && $request->filled('academic_degrees.0.specialization')) {
                $degreeData = $request->input('academic_degrees')[0]; // Lấy dữ liệu của học vị đầu tiên

                // Tạo bản ghi AcademicDegree liên kết với giảng viên vừa tạo
                $lecturer->academicDegrees()->create([
                    'degree_type_id' => $degreeData['degree_type_id'],
                    'specialization' => $degreeData['specialization'],
                    'issuing_institution' => $degreeData['issuing_institution'] ?? null,
                    'date_issued' => $degreeData['date_issued'] ?? null,
                    'notes' => $degreeData['notes'] ?? null,
                ]);
            }

            // Lưu thông tin quá trình công tác (nếu có và hợp lệ)
            if ($request->filled('work_histories.0.organization_name') && $request->filled('work_histories.0.position_held') && $request->filled('work_histories.0.start_date')) {
                $workData = $request->input('work_histories')[0]; // Lấy dữ liệu của quá trình công tác đầu tiên

                $lecturer->workHistories()->create([
                    'organization_name' => $workData['organization_name'],
                    'position_held' => $workData['position_held'],
                    'start_date' => $workData['start_date'],
                    'end_date' => $workData['end_date'] ?? null,
                    'courses_taught' => $workData['courses_taught'] ?? null,
                    'description' => $workData['description'] ?? null,
                ]);
            }

            DB::commit(); // Hoàn tất transaction, lưu tất cả thay đổi

            return redirect()->route('lecturers.index')
                             ->with('success', 'Giảng viên đã được thêm thành công.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Lỗi validation đã được Laravel tự động xử lý và redirect()->back()->withErrors()->withInput()
            // Chúng ta không cần bắt lỗi này ở đây trừ khi muốn làm gì đó đặc biệt
            DB::rollBack(); // Rollback nếu có lỗi validation sau khi đã tạo giảng viên (ít xảy ra nếu validate trước)
            throw $e; // Ném lại lỗi validation để Laravel xử lý
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback nếu có lỗi khác
            Log::error('Lỗi khi thêm giảng viên: ' . $e->getMessage() . ' --- Dòng: ' . $e->getLine() . ' --- File: ' . $e->getFile());
            return redirect()->back()
                             ->withInput() // Giữ lại dữ liệu đã nhập trên form
                             ->with('error', 'Đã có lỗi xảy ra khi thêm giảng viên. Vui lòng thử lại. Chi tiết: ' . $e->getMessage()); // Hiển thị lỗi cho người dùng khi dev
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Lecturer $lecturer): View
{
    $lecturer->load([
        'department',
        'academicDegrees.degreeType', // Eager load lồng nhau
        'workHistories'
    ]);
    return view('admin.lecturers.show', compact('lecturer'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lecturer $lecturer): View // Route Model Binding
    {
        $lecturer->load(['department', 'academicDegrees', 'workHistories']); // Tải các thông tin liên quan
        $departments = Department::orderBy('name', 'asc')->get(); // Lấy danh sách khoa

        return view('admin.lecturers.edit', compact('lecturer', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lecturer $lecturer): RedirectResponse
    {
        $validatedData = $request->validate([
            // Rule 'unique' cần bỏ qua ID của chính record đang sửa
            'lecturer_code' => 'required|string|max:50|unique:lecturers,lecturer_code,' . $lecturer->id,
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|string|in:Nam,Nữ,Khác',
            'email' => 'required|string|email|max:255|unique:lecturers,email,' . $lecturer->id,
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'academic_level' => 'required|string|max:100',
            'position' => 'nullable|string|max:100',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // Xử lý upload avatar nếu có file mới
            if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
                // Xóa avatar cũ nếu có
                if ($lecturer->avatar) {
                    Storage::disk('public')->delete($lecturer->avatar);
                }
                $avatarFile = $request->file('avatar');
                $avatarName = time() . '_' . Str::slug(pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $avatarFile->getClientOriginalExtension();
                $validatedData['avatar'] = $avatarFile->storeAs('avatars', $avatarName, 'public');
            } else {
                
            }

            $lecturer->update($validatedData);

            DB::commit();

            return redirect()->route('lecturers.show', $lecturer->id) // Chuyển về trang chi tiết sau khi sửa
                             ->with('success', 'Thông tin giảng viên đã được cập nhật thành công.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi cập nhật giảng viên: ' . $e->getMessage());
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Đã có lỗi xảy ra khi cập nhật thông tin. Vui lòng thử lại.');
        }
    }
    public function destroy(Lecturer $lecturer): RedirectResponse
    {
        DB::beginTransaction();
        try {
            // Xóa file avatar cũ nếu có
            if ($lecturer->avatar) {
                Storage::disk('public')->delete($lecturer->avatar);
            }

            $lecturer->delete();

            DB::commit();

            return redirect()->route('lecturers.index')
                             ->with('success', 'Giảng viên đã được xóa thành công.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Lỗi khi xóa giảng viên {$lecturer->id}: " . $e->getMessage());
            return redirect()->route('lecturers.index')
                             ->with('error', 'Đã có lỗi xảy ra khi xóa giảng viên. Vui lòng thử lại. Chi tiết: ' . $e->getMessage());
        }
    }
}
