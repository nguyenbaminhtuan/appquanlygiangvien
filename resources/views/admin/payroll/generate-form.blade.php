<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            {{ __('Tính tiền dạy Giảng viên') }}
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            @include('partials.session-messages')

            {{-- FORM CHỌN KÌ HỌC ĐỂ XEM TRƯỚC --}}
            <form method="POST" action="{{ route('admin.payroll.calculate-preview') }}" class="mb-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div>
                        <label for="semester_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Chọn Kì học để tính <span class="text-red-500">*</span></label>
                        <select id="semester_id" name="semester_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-gray-100">
                            <option value="">-- Chọn Kì học --</option>
                            @foreach ($semesters as $semesterLoop)
                                <option value="{{ $semesterLoop->id }}" {{ (isset($selectedSemester) && $selectedSemester->id == $semesterLoop->id) || old('semester_id') == $semesterLoop->id ? 'selected' : '' }}>
                                    {{ $semesterLoop->name }} ({{ $semesterLoop->academicYear->name }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="button-primary">Xem trước & Tính toán</button>
                    </div>
                </div>
            </form>

            {{-- KHU VỰC HIỂN THỊ KẾT QUẢ TÍNH TOÁN (SAU KHI NHẤN "XEM TRƯỚC") --}}
            @if (isset($payrollData) && $payrollData->isNotEmpty())
                <h3 class="text-lg font-semibold mt-8 mb-4 border-b pb-2 dark:border-gray-700 dark:text-gray-100">
                    Bảng kê dự kiến cho Kì học: {{ $selectedSemester->name }} ({{ $selectedSemester->academicYear->name }})
                </h3>
                <form method="POST" action="{{ route('admin.payroll.process-and-save') }}">
                    @csrf
                    <input type="hidden" name="semester_id_to_process" value="{{ $selectedSemester->id }}">
                    {{-- Thêm các input hidden khác nếu cần để truyền lại thông tin payrollData --}}

                    <div class="overflow-x-auto mt-4 rounded-lg shadow">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-3 py-3 text-left text-xs font-medium uppercase">GV</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium uppercase">Lớp HP</th>
                                    <th class="px-3 py-3 text-center text-xs font-medium uppercase">Số tiết TT</th>
                                    <th class="px-3 py-3 text-center text-xs font-medium uppercase">HS Học phần</th>
                                    <th class="px-3 py-3 text-center text-xs font-medium uppercase">Sĩ số TT</th>
                                    <th class="px-3 py-3 text-center text-xs font-medium uppercase">HS Lớp</th>
                                    <th class="px-3 py-3 text-center text-xs font-medium uppercase">Số tiết QĐ</th>
                                    <th class="px-3 py-3 text-center text-xs font-medium uppercase">HS Giảng viên</th>
                                    <th class="px-3 py-3 text-right text-xs font-medium uppercase">Đơn giá/Tiết</th>
                                    <th class="px-3 py-3 text-right text-xs font-medium uppercase">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                                @php $grandTotal = 0; @endphp
                                @foreach ($payrollData as $payment)
                                    <tr>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm">{{ $payment['lecturer_name'] }}</td>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm">{{ $payment['class_code'] }} <span class="text-xs text-gray-500">({{ $payment['subject_name'] }})</span></td>
                                        <td class="px-3 py-2 text-center whitespace-nowrap text-sm">{{ $payment['actual_teaching_hours'] }}</td>
                                        <td class="px-3 py-2 text-center whitespace-nowrap text-sm">{{ number_format($payment['subject_coefficient'], 2) }}</td>
                                        <td class="px-3 py-2 text-center whitespace-nowrap text-sm">{{ $payment['actual_students'] ?? 'N/A' }}</td>
                                        <td class="px-3 py-2 text-center whitespace-nowrap text-sm">{{ number_format($payment['class_size_coefficient'], 2) }}</td>
                                        <td class="px-3 py-2 text-center whitespace-nowrap text-sm font-semibold">{{ number_format($payment['converted_teaching_units'], 2) }}</td>
                                        <td class="px-3 py-2 text-center whitespace-nowrap text-sm">{{ number_format($payment['lecturer_coefficient'], 2) }}</td>
                                        <td class="px-3 py-2 text-right whitespace-nowrap text-sm">{{ number_format($payment['base_rate'], 0) }}</td>
                                        <td class="px-3 py-2 text-right whitespace-nowrap text-sm font-semibold">{{ number_format($payment['payment_amount'], 0) }}</td>
                                    </tr>
                                    @php $grandTotal += $payment['payment_amount']; @endphp
                                @endforeach
                                <tr>
                                    <td colspan="9" class="px-3 py-2 text-right font-bold text-sm uppercase">Tổng cộng dự kiến:</td>
                                    <td class="px-3 py-2 text-right font-bold text-sm">{{ number_format($grandTotal, 0) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" name="action" value="save_payroll" class="button-success">Chốt và Lưu Bảng lương này</button>
                    </div>
                </form>
            @elseif(isset($selectedSemester) && request()->isMethod('post'))
                <p class="mt-6 text-gray-600 dark:text-gray-400">Không có lớp học phần nào được phân công giảng viên trong kì học đã chọn hoặc không có đủ thông tin để tính toán.</p>
            @endif
        </div>
    </div>
</x-admin-layout>