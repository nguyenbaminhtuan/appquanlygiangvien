<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight   ">
            {{ __('Phân công Giảng dạy') }}
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">

            {{-- FORM LỌC THEO KÌ HỌC --}}
            <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
                <form method="GET" action="{{ route('admin.assignments.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="semester_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Chọn Kì học</label>
                            <select name="semester_id" id="semester_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-gray-100">
                                <option value="">-- Tất cả các kì (hoặc kì mới nhất) --</option>
                                @foreach ($semesters as $semester)
                                    <option value="{{ $semester->id }}" {{ $selectedSemesterId == $semester->id ? 'selected' : '' }}>
                                        {{ $semester->name }} ({{ $semester->academicYear->name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs uppercase rounded-md">
                                Lọc
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            @if (session('success'))
                <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Mã Lớp</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Học phần</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Kì học</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Lịch học</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Sĩ số tối đa</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase min-w-[250px]">Phân công Giảng viên</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($scheduledClasses as $class)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">{{ $class->class_code }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                    {{ $class->subject->name ?? 'N/A' }}
                                    <span class="block text-xs text-gray-500">{{ $class->subject->subject_code ?? '' }}</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $class->semester->name ?? 'N/A' }} ({{ $class->semester->academicYear->name ?? '' }})</td>
                                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{!! nl2br(e($class->schedule_info)) ?? 'N/A' !!}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-center">{{ $class->max_students }}</td>
                               <td class="px-4 py-3 whitespace-nowrap text-sm">
                                    <form method="POST" action="{{ route('admin.assignments.assign') }}">
                                        @csrf
                                        <input type="hidden" name="scheduled_class_id" value="{{ $class->id }}">
                                        <div class="flex items-center">
                                            <select name="lecturer_id"
                                                    class="block w-full rounded-md shadow-sm sm:text-sm
                                                        border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50
                                                        dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:focus:border-indigo-500 dark:focus:ring-indigo-500">
                                                        {{-- Các class chính cho thẻ select --}}
                                                <option value="" class="text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-700">-- Chọn Giảng viên --</option>
                                                @foreach ($lecturersWithLoad as $lecturer)
                                                    <option value="{{ $lecturer->id }}"
                                                            {{ $class->lecturer_id == $lecturer->id ? 'selected' : '' }}
                                                            class="text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                            {{-- Class cho từng option để đảm bảo màu chữ --}}
                                                        {{ $lecturer->full_name }} ({{ $lecturer->lecturer_code }})
                                                        - Tải: {{ $lecturer->scheduled_classes_count ?? 0 }} lớp, {{ $lecturer->total_credits ?? 0 }} TC
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="ml-2 inline-flex items-center px-3 py-1.5 bg-indigo-500 hover:bg-indigo-600 text-white text-xs font-semibold rounded-md">
                                                Lưu
                                            </button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500 dark:text-gray-300">
                                    Không có lớp học phần nào phù hợp với bộ lọc.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $scheduledClasses->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>