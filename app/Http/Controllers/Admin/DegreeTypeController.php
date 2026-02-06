<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DegreeType;  
use Illuminate\Http\Request;    
use Illuminate\View\View;          
use Illuminate\Http\RedirectResponse; 
use Illuminate\Support\Facades\Log; 

class DegreeTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $degreeTypes = DegreeType::orderBy('name')->paginate(10);
        return view('admin.degree-types.index', compact('degreeTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.degree-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:degree_types,name',
            'abbreviation' => 'nullable|string|max:50|unique:degree_types,abbreviation',
            'description' => 'nullable|string',
        ]);

        try {
            DegreeType::create($validatedData);
            return redirect()->route('degree-types.index')->with('success', 'Danh mục bằng cấp đã được tạo thành công.');
        } catch (\Exception $e) {
            Log::error("Lỗi khi tạo DegreeType: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Đã có lỗi xảy ra khi tạo danh mục bằng cấp.');
        }
    }

    /**
     * Display the specified resource.
     * (Thường không dùng trực tiếp cho quản lý danh mục, có thể bỏ qua nếu không có view show riêng)
     */
    public function show()
    {
        
    }

    public function edit(DegreeType $degreeType): View // Route Model Binding
    {
        return view('admin.degree-types.edit', compact('degreeType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DegreeType $degreeType): RedirectResponse // Route Model Binding
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:degree_types,name,' . $degreeType->id,
            'abbreviation' => 'nullable|string|max:50|unique:degree_types,abbreviation,' . $degreeType->id,
            'description' => 'nullable|string',
        ]);

        try {
            $degreeType->update($validatedData);
            return redirect()->route('degree-types.index')->with('success', 'Danh mục bằng cấp đã được cập nhật thành công.');
        } catch (\Exception $e) {
            Log::error("Lỗi khi cập nhật DegreeType {$degreeType->id}: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Đã có lỗi xảy ra khi cập nhật danh mục bằng cấp.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DegreeType $degreeType): RedirectResponse // Route Model Binding
    {
        try {
            // Kiểm tra xem có academic_degree nào đang sử dụng degree_type này không
            if ($degreeType->academicDegrees()->exists()) { // Hoặc ->count() > 0
                return redirect()->route('degree-types.index')->with('error', 'Không thể xóa danh mục này vì đang được sử dụng bởi ít nhất một học vị của giảng viên.');
            }
            $degreeType->delete();
            return redirect()->route('degree-types.index')->with('success', 'Danh mục bằng cấp đã được xóa thành công.');
        } catch (\Exception $e) {
            Log::error("Lỗi khi xóa DegreeType {$degreeType->id}: " . $e->getMessage());
            return redirect()->route('degree-types.index')->with('error', 'Đã có lỗi xảy ra khi xóa danh mục bằng cấp.');
        }
    }
}