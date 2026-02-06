<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\View\View; // Import View
use Illuminate\Http\RedirectResponse;
class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View // Kiểu trả về là View
    {
        // Lấy tất cả các khoa, sắp xếp theo tên, phân trang (ví dụ 10 khoa/trang)
        $departments = Department::orderBy('name', 'asc')->paginate(10);

        // Trả về view 'admin.departments.index' và truyền biến $departments
        return view('admin.departments.index', compact('departments'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(): View 
    {
        return view('admin.departments.create'); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse // Thêm phương thức này
    {
        // Validate dữ liệu đầu vào
        $validatedData = $request->validate([
            'code' => 'required|string|max:50|unique:departments,code', // Mã khoa là bắt buộc, duy nhất trong bảng departments
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Tạo khoa mới với dữ liệu đã được validate
        Department::create($validatedData);

        // Chuyển hướng về trang danh sách khoa với thông báo thành công
        return redirect()->route('departments.index')
                         ->with('success', 'Khoa/Bộ môn đã được tạo thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department): View // Sử dụng Route Model Binding
    {
        // Biến $department sẽ tự động được inject dựa trên ID từ URL
        // Ví dụ: /admin/departments/5/edit -> $department sẽ là khoa có ID = 5
        return view('admin.departments.edit', compact('department'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department): RedirectResponse // Thêm phương thức này
    {
        // Validate dữ liệu đầu vào
        $validatedData = $request->validate([
            // Khi update, rule 'unique' cần bỏ qua ID của chính record đang được sửa
            'code' => 'required|string|max:50|unique:departments,code,' . $department->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Cập nhật khoa với dữ liệu đã được validate
        $department->update($validatedData);

        // Chuyển hướng về trang danh sách khoa với thông báo thành công
        return redirect()->route('departments.index')
                         ->with('success', 'Thông tin Khoa/Bộ môn đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department): RedirectResponse // Thêm phương thức này
    {
        // Trước khi xóa, bạn có thể muốn kiểm tra xem có giảng viên nào thuộc khoa này không
        // Nếu có, bạn có thể không cho xóa hoặc thông báo cho người dùng.
        // Ví dụ:
        if ($department->lecturers()->count() > 0) {
            return redirect()->route('departments.index')
                             ->with('error', 'Không thể xóa Khoa/Bộ môn này vì vẫn còn giảng viên trực thuộc.');
        }

        // Nếu không có ràng buộc, tiến hành xóa
        try {
            $department->delete(); // Xóa khoa khỏi CSDL
            return redirect()->route('departments.index')
                             ->with('success', 'Khoa/Bộ môn đã được xóa thành công.');
        } catch (\Exception $e) {
            // Ghi log lỗi nếu cần: Log::error($e->getMessage());
            return redirect()->route('departments.index')
                             ->with('error', 'Có lỗi xảy ra khi xóa Khoa/Bộ môn.');
        }
    }
}
