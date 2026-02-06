<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            {{ __('Thêm Kì học mới') }}
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            @if ($errors->any())
                <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-900 dark:text-red-300" role="alert">
                    <div class="font-medium">{{ __('Rất tiếc! Có lỗi xảy ra.') }}</div>
                    <ul class="mt-1 list-disc list-inside text-sm text-red-600 dark:text-red-400">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('semesters.store') }}">
                @csrf
                <div class="mb-4">
                    <label for="academic_year_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Năm học') }} <span class="text-red-500">*</span></label>
                    <select id="academic_year_id" name="academic_year_id" required
                            class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500">
                        <option value="" class="text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-700">-- Chọn Năm học --</option>
                        @foreach ($academicYears as $year)
                            <option value="{{ $year->id }}" {{ old('academic_year_id') == $year->id ? 'selected' : '' }} class="text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700">
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Tên Kì học') }} <span class="text-red-500">*</span></label>
                    <input id="name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500"
                           type="text" name="name" value="{{ old('name') }}" placeholder="Ví dụ: Học kì 1, Học kì Hè" required />
                </div>

                <div class="mb-4">
                    <label for="start_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Ngày bắt đầu') }} <span class="text-red-500">*</span></label>
                    <input id="start_date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500"
                           type="date" name="start_date" value="{{ old('start_date') }}" required />
                </div>

                <div class="mb-4">
                    <label for="end_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Ngày kết thúc') }} <span class="text-red-500">*</span></label>
                    <input id="end_date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500"
                           type="date" name="end_date" value="{{ old('end_date') }}" required />
                </div>

                <div class="block mt-4">
                    <label for="is_current" class="inline-flex items-center">
                        <input id="is_current" type="checkbox" name="is_current" value="1" {{ old('is_current') ? 'checked' : '' }}
                               class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                        <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Đặt làm kì học hiện tại?') }}</span>
                    </label>
                     <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Lưu ý: Nếu chọn, các kì học khác sẽ không còn là "hiện tại".</p>
                </div>


                <div class="flex items-center justify-end mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('semesters.index') }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md">
                        {{ __('Hủy') }}
                    </a>
                    <button type="submit"
                            class="ms-4 inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        {{ __('Lưu Kì học') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>