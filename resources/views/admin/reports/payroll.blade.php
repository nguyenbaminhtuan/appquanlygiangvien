<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            {{ __('Báo cáo Tiền dạy Giảng viên') }}
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            @include('partials.session-messages')

            {{-- FORM LỌC --}}
            <div class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                <form method="GET" action="{{ route('admin.reports.payroll') }}">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                        {{-- Lọc theo Năm học --}}
                        <div>
                            <label for="academic_year_id" class="block text-sm font-medium">Năm học</label>
                            <select name="academic_year_id" id="academic_year_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-gray-100">
                                <option value="">-- Tất cả Năm học --</option>
                                @foreach ($academicYears as $year)
                                    <option value="{{ $year->id }}" {{ $selectedAcademicYearId == $year->id ? 'selected' : '' }}>{{ $year->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Lọc theo Kì học --}}
                        <div>
                            <label for="semester_id" class="block text-sm font-medium">Kì học</label>
                            <select name="semester_id" id="semester_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-gray-100">
                                <option value="">-- Tất cả Kì học --</option>
                                @foreach ($semesters as $semester)
                                    <option value="{{ $semester->id }}" {{ $selectedSemesterId == $semester->id ? 'selected' : '' }}>{{ $semester->name }} ({{ $semester->academicYear->name }})</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Lọc theo Khoa --}}
                        <div>
                            <label for="department_id" class="block text-sm font-medium">Khoa</label>
                            <select name="department_id" id="department_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-gray-100">
                                <option value="">-- Tất cả Khoa --</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" {{ $selectedDepartmentId == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Lọc theo Giảng viên --}}
                        <div>
                            <label for="lecturer_id" class="block text-sm font-medium">Giảng viên</label>
                            <select name="lecturer_id" id="lecturer_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-gray-100">
                                <option value="">-- Tất cả Giảng viên --</option>
                                @foreach ($lecturers as $lecturer)
                                    <option value="{{ $lecturer->id }}" {{ $selectedLecturerId == $lecturer->id ? 'selected' : '' }}>{{ $lecturer->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" class="button-primary">Xem báo cáo</button>
                            <a href="{{ route('admin.reports.payroll') }}" class="ml-2 button-secondary">Xóa lọc</a>
                        </div>
                    </div>
                </form>
            </div>
            {{-- THẺ TÓM TẮT --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                 <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg shadow">
                     <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Tổng số khoản thanh toán</p>
                     <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalPaymentsCount }}</p>
                 </div>
                 <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg shadow">
                     <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Tổng tiền</p>
                     <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($grandTotal, 0) }}</p>
                 </div>
            </div>

            {{-- BẢNG KẾT QUẢ --}}
            <div class="overflow-x-auto rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Giảng viên</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Khoa</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Lớp HP</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Kì học</th>
                            <th class="px-4 py-3 text-right text-xs font-medium uppercase">Thành tiền</th>
                            <th class="px-4 py-3 text-center text-xs font-medium uppercase">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                        @forelse ($payrollDetails as $payment)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">{{ $payment->lecturer->full_name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $payment->lecturer->department->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $payment->scheduledClass->class_code ?? 'N/A' }} <span class="block text-xs text-gray-500">({{ $payment->scheduledClass->subject->name ?? '' }})</span></td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $payment->semester->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-right whitespace-nowrap text-sm font-semibold">{{ number_format($payment->payment_amount, 0) }}</td>
                                <td class="px-4 py-3 text-center whitespace-nowrap text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($payment->status == 'paid') bg-green-100 text-green-800 @elseif($payment->status == 'approved') bg-blue-100 text-blue-800 @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm">Không có dữ liệu thanh toán phù hợp với bộ lọc.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $payrollDetails->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>