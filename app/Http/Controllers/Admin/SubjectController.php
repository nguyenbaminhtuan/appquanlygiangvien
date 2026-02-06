<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Department; // Import Department
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule; // Import Rule

class SubjectController extends Controller
{
    public function index(): View
    {
        $subjects = Subject::with('department') // Eager load department
                           ->orderBy('name')->paginate(10);
        return view('admin.subjects.index', compact('subjects'));
    }

    public function create(): View
    {
        $departments = Department::orderBy('name')->get(); // Lấy danh sách khoa
        return view('admin.subjects.create', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'subject_code' => 'required|string|max:50|unique:subjects,subject_code',
            'name' => 'required|string|max:255',
            'credits' => 'required|integer|min:0|max:15', // Max 15 tín chỉ là ví dụ
            'default_teaching_hours' => 'required|integer|min:0', 
            'subject_coefficient' => 'required|numeric|min:1.0|max:2.0',
            'department_id' => 'nullable|exists:departments,id',
            'description' => 'nullable|string',
        ]);

        try {
            Subject::create($validatedData);
            return redirect()->route('subjects.index')->with('success', 'Học phần đã được tạo thành công.');
        } catch (\Exception $e) {
            Log::error("Lỗi khi tạo học phần: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Đã có lỗi xảy ra khi tạo học phần.');
        }
    }

    public function edit(Subject $subject): View
    {
        $departments = Department::orderBy('name')->get();
        return view('admin.subjects.edit', compact('subject', 'departments'));
    }

    public function update(Request $request, Subject $subject): RedirectResponse
    {
        $validatedData = $request->validate([
            'subject_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('subjects')->ignore($subject->id),
            ],
            'name' => 'required|string|max:255',
            'credits' => 'required|integer|min:0|max:15',
            'department_id' => 'nullable|exists:departments,id',
            'description' => 'nullable|string',
        ]);

        try {
            $subject->update($validatedData);
            return redirect()->route('subjects.index')->with('success', 'Học phần đã được cập nhật thành công.');
        } catch (\Exception $e) {
            Log::error("Lỗi khi cập nhật học phần {$subject->id}: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Đã có lỗi xảy ra khi cập nhật học phần.');
        }
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        try {
            // Kiểm tra ràng buộc với ScheduledClass (Lớp học phần) trước khi xóa
            if ($subject->scheduledClasses()->exists()) {
                return redirect()->route('subjects.index')
                                 ->with('error', 'Không thể xóa học phần này vì đang được sử dụng trong các lớp học phần.');
            }
            $subject->delete();
            return redirect()->route('subjects.index')->with('success', 'Học phần đã được xóa thành công.');
        } catch (\Exception $e) {
            Log::error("Lỗi khi xóa học phần {$subject->id}: " . $e->getMessage());
            return redirect()->route('subjects.index')->with('error', 'Đã có lỗi xảy ra khi xóa học phần.');
        }
    }
}