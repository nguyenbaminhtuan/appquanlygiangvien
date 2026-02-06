<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            {{ __('Chỉnh sửa Lớp học phần: ') }} {{ $scheduledClass->class_code }}
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            @if ($errors->any())
                <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-900 dark:text-red-300" role="alert">
                    <div class="font-medium">{{ __('Rất tiếc! Có lỗi xảy ra.') }}</div>
                    <ul class="mt-1 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            {{-- ... thông báo success/error ... --}}

            <form method="POST" action="{{ route('scheduled-classes.update', $scheduledClass->id) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="semester_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Kì học <span class="text-red-500">*</span></label>
                        <select id="semester_id" name="semester_id" required
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500">
                            <option value="" class="text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-700">-- Chọn Kì học --</option>
                            @foreach ($semesters as $semester)
                                <option value="{{ $semester->id }}" {{ old('semester_id', $scheduledClass->semester_id) == $semester->id ? 'selected' : '' }} class="text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700">
                                    {{ $semester->name }} ({{ $semester->academicYear->name }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="subject_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Học phần <span class="text-red-500">*</span></label>
                        <select id="subject_id" name="subject_id" required
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500">
                            <option value="" class="text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-700">-- Chọn Học phần --</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id', $scheduledClass->subject_id) == $subject->id ? 'selected' : '' }} class="text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700">
                                    {{ $subject->name }} ({{ $subject->subject_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="class_code" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Mã Lớp <span class="text-red-500">*</span></label>
                        <input id="class_code" type="text" name="class_code" value="{{ old('class_code', $scheduledClass->class_code) }}" required
                               class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="max_students" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Sĩ số tối đa <span class="text-red-500">*</span></label>
                        <input id="max_students" type="number" name="max_students" value="{{ old('max_students', $scheduledClass->max_students) }}" min="1" max="200" required
                               class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500">
                    </div>

                    {{-- TRƯỜNG SỬA SỐ TIẾT THỰC TẾ --}}
                    <div>
                        <label for="actual_teaching_hours" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Số tiết thực tế/lớp</label>
                        <input id="actual_teaching_hours" type="number" name="actual_teaching_hours" value="{{ old('actual_teaching_hours', $scheduledClass->actual_teaching_hours) }}" min="0"
                               class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500">
                         <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Số tiết chuẩn của học phần: {{ $scheduledClass->subject->default_teaching_hours ?? 'N/A' }}</p>
                    </div>

                    {{-- TRƯỜNG SỬA SĨ SỐ THỰC TẾ --}}
                    <div>
                        <label for="actual_students" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Sĩ số thực tế</label>
                        <input id="actual_students" type="number" name="actual_students" value="{{ old('actual_students', $scheduledClass->actual_students) }}" min="0"
                               class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500">
                    </div>


                    <div class="md:col-span-2">
                        <label for="schedule_info" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Thông tin Lịch học (Thứ, Tiết, Phòng)</label>
                        <textarea id="schedule_info" name="schedule_info" rows="3"
                                  class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500">{{ old('schedule_info', $scheduledClass->schedule_info) }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label for="lecturer_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Giảng viên Phụ trách</label>
                        <select id="lecturer_id" name="lecturer_id"
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500">
                            <option value="" class="text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-700">-- Chưa phân công --</option>
                            @foreach ($lecturers as $lecturer)
                                <option value="{{ $lecturer->id }}" {{ old('lecturer_id', $scheduledClass->lecturer_id) == $lecturer->id ? 'selected' : '' }} class="text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700">
                                    {{ $lecturer->full_name }} ({{ $lecturer->lecturer_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label for="notes" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Ghi chú</label>
                        <textarea id="notes" name="notes" rows="2"
                                  class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500">{{ old('notes', $scheduledClass->notes) }}</textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('scheduled-classes.index') }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md">
                        {{ __('Hủy') }}
                    </a>
                    <button type="submit"
                            class="ms-4 inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        {{ __('Cập nhật Lớp học') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>