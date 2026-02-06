<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSizeCoefficient;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ClassSizeCoefficientController extends Controller
{
    public function index(): View
    {
        $classSizeCoefficients = ClassSizeCoefficient::orderBy('min_students')->paginate(10);
        return view('admin.class-size-coefficients.index', compact('classSizeCoefficients'));
    }

    public function create(): View
    {
        return view('admin.class-size-coefficients.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'min_students' => 'required|integer|min:0',
            'max_students' => 'nullable|integer|min:0|gte:min_students', // gte: greater than or equal
            'coefficient' => 'required|numeric',
        ]);

        // Kiểm tra tính duy nhất của khoảng sĩ số (phức tạp hơn, có thể bỏ qua ban đầu nếu các khoảng không giao nhau)
        // Ví dụ: không cho tạo (20-30) nếu đã có (25-35)
        // Bạn có thể thêm custom validation rule cho việc này

        try {
            ClassSizeCoefficient::create($validatedData);
            return redirect()->route('class-size-coefficients.index')->with('success', 'Hệ số sĩ số lớp đã được tạo.');
        } catch (\Exception $e) {
            Log::error("Lỗi tạo ClassSizeCoefficient: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Lỗi khi tạo hệ số sĩ số lớp.');
        }
    }

    public function edit(ClassSizeCoefficient $classSizeCoefficient): View
    {
        return view('admin.class-size-coefficients.edit', compact('classSizeCoefficient'));
    }

    public function update(Request $request, ClassSizeCoefficient $classSizeCoefficient): RedirectResponse
    {
        $validatedData = $request->validate([
            'min_students' => 'required|integer|min:0',
            'max_students' => 'nullable|integer|min:0|gte:min_students',
            'coefficient' => 'required|numeric',
        ]);

        try {
            $classSizeCoefficient->update($validatedData);
            return redirect()->route('class-size-coefficients.index')->with('success', 'Hệ số sĩ số lớp đã được cập nhật.');
        } catch (\Exception $e) {
            Log::error("Lỗi cập nhật ClassSizeCoefficient {$classSizeCoefficient->id}: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Lỗi khi cập nhật hệ số sĩ số lớp.');
        }
    }

    public function destroy(ClassSizeCoefficient $classSizeCoefficient): RedirectResponse
    {
        try {
            // Hiện tại không có ràng buộc trực tiếp, nhưng sau này có thể kiểm tra
            $classSizeCoefficient->delete();
            return redirect()->route('class-size-coefficients.index')->with('success', 'Hệ số sĩ số lớp đã được xóa.');
        } catch (\Exception $e) {
            Log::error("Lỗi xóa ClassSizeCoefficient {$classSizeCoefficient->id}: " . $e->getMessage());
            return redirect()->route('class-size-coefficients.index')->with('error', 'Lỗi khi xóa hệ số sĩ số lớp.');
        }
    }
}