<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LecturerPayRate;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class LecturerPayRateController extends Controller
{
    public function index(): View
    {
        $lecturerPayRates = LecturerPayRate::orderBy('academic_level_or_title')->paginate(10);
        return view('admin.lecturer-pay-rates.index', compact('lecturerPayRates'));
    }

    public function create(): View
    {
        return view('admin.lecturer-pay-rates.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'academic_level_or_title' => 'required|string|max:255|unique:lecturer_pay_rates,academic_level_or_title',
            'coefficient' => 'required|numeric|min:0',
            'effective_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        try {
            LecturerPayRate::create($validatedData);
            return redirect()->route('lecturer-pay-rates.index')->with('success', 'Hệ số lương đã được tạo.');
        } catch (\Exception $e) {
            Log::error("Lỗi tạo LecturerPayRate: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Lỗi khi tạo hệ số lương.');
        }
    }

    public function edit(LecturerPayRate $lecturerPayRate): View
    {
        return view('admin.lecturer-pay-rates.edit', compact('lecturerPayRate'));
    }

    public function update(Request $request, LecturerPayRate $lecturerPayRate): RedirectResponse
    {
        $validatedData = $request->validate([
            'academic_level_or_title' => [
                'required','string','max:255',
                Rule::unique('lecturer_pay_rates')->ignore($lecturerPayRate->id),
            ],
            'coefficient' => 'required|numeric|min:0',
            'effective_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        try {
            $lecturerPayRate->update($validatedData);
            return redirect()->route('lecturer-pay-rates.index')->with('success', 'Hệ số lương đã được cập nhật.');
        } catch (\Exception $e) {
            Log::error("Lỗi cập nhật LecturerPayRate {$lecturerPayRate->id}: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Lỗi khi cập nhật hệ số lương.');
        }
    }

    public function destroy(LecturerPayRate $lecturerPayRate): RedirectResponse
    {
        try {
            // Cân nhắc: kiểm tra xem hệ số này có đang được sử dụng ở đâu không trước khi xóa
            $lecturerPayRate->delete();
            return redirect()->route('lecturer-pay-rates.index')->with('success', 'Hệ số lương đã được xóa.');
        } catch (\Exception $e) {
            Log::error("Lỗi xóa LecturerPayRate {$lecturerPayRate->id}: " . $e->getMessage());
            return redirect()->route('lecturer-pay-rates.index')->with('error', 'Lỗi khi xóa hệ số lương.');
        }
    }
}