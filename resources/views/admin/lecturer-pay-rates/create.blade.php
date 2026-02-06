<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            {{ __('Thêm mới Hệ số lương Giảng viên') }}
        </h2>
    </x-slot>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            @include('partials.session-messages')
            <form method="POST" action="{{ route('lecturer-pay-rates.store') }}">
                @csrf
                <div class="mb-4">
                    <label for="academic_level_or_title" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Trình độ/Chức danh <span class="text-red-500">*</span></label>
                    <input id="academic_level_or_title" type="text" name="academic_level_or_title" value="{{ old('academic_level_or_title') }}" required class="input-field-darkable mt-1 block w-full">
                    <p class="text-xs text-gray-500 mt-1">Ví dụ: Thạc sỹ, Tiến sỹ, Giáo sư. Phải là duy nhất.</p>
                </div>
                <div class="mb-4">
                    <label for="coefficient" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Hệ số <span class="text-red-500">*</span></label>
                    <input id="coefficient" type="number" name="coefficient" value="{{ old('coefficient', 1.0) }}" step="0.01" min="0" required class="input-field-darkable mt-1 block w-full">
                </div>
                <div class="mb-4">
                    <label for="effective_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Ngày hiệu lực <span class="text-red-500">*</span></label>
                    <input id="effective_date" type="date" name="effective_date" value="{{ old('effective_date', now()->format('Y-m-d')) }}" required class="input-field-darkable mt-1 block w-full">
                </div>
                <div class="mb-4">
                    <label for="notes" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Ghi chú</label>
                    <textarea id="notes" name="notes" rows="3" class="input-field-darkable mt-1 block w-full">{{ old('notes') }}</textarea>
                </div>
                <div class="flex items-center justify-end mt-6 pt-6 border-t dark:border-gray-700">
                    <a href="{{ route('lecturer-pay-rates.index') }}" class="underline text-sm">Hủy</a>
                    <button type="submit" class="ms-4 button-primary">Lưu Hệ số</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>