<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            {{ __('Thêm mới Hệ số Sĩ số Lớp') }}
        </h2>
    </x-slot>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            @include('partials.session-messages')
            <form method="POST" action="{{ route('class-size-coefficients.store') }}">
                @csrf
                <div class="mb-4">
                    <label for="min_students" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Sĩ số Tối thiểu <span class="text-red-500">*</span></label>
                    <input id="min_students" type="number" name="min_students" value="{{ old('min_students', 0) }}" required min="0" class="input-field-darkable mt-1 block w-full">
                </div>
                <div class="mb-4">
                    <label for="max_students" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Sĩ số Tối đa (Để trống nếu không có giới hạn trên)</label>
                    <input id="max_students" type="number" name="max_students" value="{{ old('max_students') }}" min="0" class="input-field-darkable mt-1 block w-full">
                </div>
                <div class="mb-4">
                    <label for="coefficient" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Hệ số <span class="text-red-500">*</span></label>
                    <input id="coefficient" type="number" name="coefficient" value="{{ old('coefficient', 0.0) }}" step="0.01" required class="input-field-darkable mt-1 block w-full">
                </div>
                <div class="flex items-center justify-end mt-6 pt-6 border-t dark:border-gray-700">
                    <a href="{{ route('class-size-coefficients.index') }}" class="underline text-sm">Hủy</a>
                    <button type="submit" class="ms-4 button-primary">Lưu Hệ số</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>