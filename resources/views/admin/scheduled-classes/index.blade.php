<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            {{ __('Quản lý Lớp học phần') }}
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">

            {{-- FORM LỌC --}}
            <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
                <form method="GET" action="{{ route('scheduled-classes.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="search_term" class="block text-sm font-medium">Mã lớp/Tên HP/Mã HP</label>
                            <input type="text" name="search_term" id="search_term" value="{{ request('search_term') }}"
                                   class="mt-1 block w-full rounded-md shadow-sm sm:text-sm dark:bg-gray-600">
                        </div>
                        <div>
                            <label for="filter_semester_id" class="block text-sm font-medium">Kì học</label>
                            <select name="filter_semester_id" id="filter_semester_id"
                                    class="mt-1 block w-full rounded-md shadow-sm sm:text-sm dark:bg-gray-600">
                                <option value="">-- Tất cả Kì học --</option>
                                @foreach ($semesters as $semester)
                                    <option value="{{ $semester->id }}" {{ request('filter_semester_id') == $semester->id ? 'selected' : '' }}>
                                        {{ $semester->name }} ({{ $semester->academicYear->name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="filter_subject_id" class="block text-sm font-medium">Học phần</label>
                            <select name="filter_subject_id" id="filter_subject_id"
                                    class="mt-1 block w-full rounded-md shadow-sm sm:text-sm dark:bg-gray-600">
                                <option value="">-- Tất cả Học phần --</option>
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ request('filter_subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }} ({{ $subject->subject_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs uppercase rounded-md">
                                Lọc
                            </button>
                            <a href="{{ route('scheduled-classes.index') }}" class="ml-2 inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold text-xs uppercase rounded-md">
                                Xóa lọc
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Nút Thêm mới (nếu bạn muốn có form thêm thủ công từng lớp) --}}
            {{-- <div class="mb-4">
                <a href="{{ route('scheduled-classes.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 border rounded-md font-semibold text-xs text-white uppercase">
                    {{ __('Thêm Lớp HP mới') }}
                </a>
            </div> --}}

            @if (session('success'))
                <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">STT</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Mã Lớp</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Học phần</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Kì học</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Giảng viên</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Sĩ số tối đa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Lịch học</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($scheduledClasses as $index => $class)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $scheduledClasses->firstItem() + $index }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $class->class_code }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    {{ $class->subject->name ?? 'N/A' }}
                                    <span class="block text-xs text-gray-500">{{ $class->subject->subject_code ?? '' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $class->semester->name ?? 'N/A' }} ({{ $class->semester->academicYear->name ?? '' }})</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $class->lecturer->full_name ?? 'Chưa phân công' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">{{ $class->max_students }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {!! nl2br(e($class->schedule_info)) ?? 'N/A' !!}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('scheduled-classes.edit', $class->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 mr-3">Sửa</a>
                                    <form action="{{ route('scheduled-classes.destroy', $class->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa lớp học phần này không?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500 dark:text-gray-300">Không có lớp học phần nào.</td>
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