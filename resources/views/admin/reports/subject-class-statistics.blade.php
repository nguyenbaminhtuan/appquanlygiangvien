<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            {{ __('Thống kê Số lớp mở theo Học phần/Năm học') }}
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">

            {{-- FORM LỌC --}}
            <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
                <form method="GET" action="{{ route('admin.reports.subject-class-statistics') }}">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="academic_year_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Chọn Năm học <span class="text-red-500">*</span></label>
                            <select name="academic_year_id" id="academic_year_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-gray-100">
                                <option value="">-- Chọn Năm học --</option>
                                @foreach ($academicYears as $year)
                                    <option value="{{ $year->id }}" {{ $selectedAcademicYearId == $year->id ? 'selected' : '' }}>
                                        {{ $year->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="department_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Khoa (Tùy chọn)</label>
                            <select name="department_id" id="department_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-gray-100">
                                <option value="">-- Tất cả Khoa --</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" {{ $selectedDepartmentId == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs uppercase rounded-md">
                                Xem Thống kê
                            </button>
                             <a href="{{ route('admin.reports.subject-class-statistics') }}" class="ml-2 inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold text-xs uppercase rounded-md">
                                Xóa lọc
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            @if (!empty($statistics))
                <h3 class="text-lg font-semibold mb-3">
                    Kết quả thống kê cho: {{ $academicYears->find($selectedAcademicYearId)->name ?? '' }}
                    @if($selectedDepartmentId)
                        - Khoa: {{ $departments->find($selectedDepartmentId)->name ?? '' }}
                    @endif
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 border dark:border-gray-600">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase">Học phần</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase">Khoa QL</th>
                                @foreach ($semestersInYear as $semester)
                                    <th class="px-4 py-3 text-center text-xs font-medium uppercase">{{ $semester->name }}</th>
                                @endforeach
                                <th class="px-4 py-3 text-center text-xs font-medium uppercase">Tổng cộng (Năm)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                            @forelse ($statistics as $stat)
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        {{ $stat['subject_name'] }}
                                        <span class="block text-xs text-gray-500">({{ $stat['subject_code'] }})</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $stat['department_name'] }}</td>
                                    @foreach ($semestersInYear as $semester)
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                                            {{ $stat['stats_by_semester'][$semester->id] ?? 0 }}
                                        </td>
                                    @endforeach
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-center font-semibold">{{ $stat['total_in_year'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ 4 + $semestersInYear->count() }}" class="px-6 py-4 text-center text-sm">
                                        Không có dữ liệu thống kê cho lựa chọn này.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @elseif ($selectedAcademicYearId)
                <p class="text-gray-600 dark:text-gray-400">Không có dữ liệu thống kê cho năm học đã chọn hoặc không có học phần nào phù hợp.</p>
            @else
                <p class="text-gray-600 dark:text-gray-400">Vui lòng chọn một Năm học để xem thống kê.</p>
            @endif
        </div>
    </div>
</x-admin-layout>