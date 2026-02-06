<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            {{ __('Thêm Học phần mới') }}
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            @if ($errors->any())
                <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
                    <div class="font-medium">{{ __('Rất tiếc! Có lỗi xảy ra.') }}</div>
                    <ul class="mt-1 list-disc list-inside text-sm text-red-600 dark:text-red-400">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('subjects.store') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="subject_code" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Mã Học phần') }} <span class="text-red-500">*</span></label>
                        <input id="subject_code" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                               type="text" name="subject_code" value="{{ old('subject_code') }}" placeholder="Ví dụ: IT101" required autofocus />
                    </div>

                    <div>
                        <label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Tên Học phần') }} <span class="text-red-500">*</span></label>
                        <input id="name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                               type="text" name="name" value="{{ old('name') }}" placeholder="Ví dụ: Nhập môn Lập trình" required />
                    </div>

                    <div>
                        <label for="credits" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Số tín chỉ') }} <span class="text-red-500">*</span></label>
                        <input id="credits" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                               type="number" name="credits" value="{{ old('credits') }}" min="0" max="15" placeholder="Ví dụ: 3" required />
                    </div>

                    <div>
                        <label for="default_teaching_hours" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Số tiết chuẩn') }} <span class="text-red-500">*</span></label>
                        <input id="default_teaching_hours" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500"
                            type="number" name="default_teaching_hours" value="{{ old('default_teaching_hours', 30) }}" min="0" required />
                    </div>

                        <div>
                            <label for="subject_coefficient" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Hệ số Học phần') }} <span class="text-red-500">*</span></label>
                            <input id="subject_coefficient" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500"
                                type="number" name="subject_coefficient" value="{{ old('subject_coefficient', 1.0) }}" step="0.01" min="1.0" max="2.0" required /> {{-- Giả sử max là 2.0 --}}
                        </div>

                    <div>
                        <label for="department_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Khoa quản lý (Tùy chọn)') }}</label>
                        <select id="department_id" name="department_id"
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                            <option value="">-- Không chọn Khoa --</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <label for="description" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Mô tả') }}</label>
                    <textarea id="description" name="description" rows="4"
                              class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500"{{ old('address') }}</textarea>>{{ old('description') }}</textarea>
                </div>


                <div class="flex items-center justify-end mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('subjects.index') }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md">
                        {{ __('Hủy') }}
                    </a>
                    <button type="submit"
                            class="ms-4 inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest">
                        {{ __('Lưu Học phần') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>