<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Thêm Học vị/Học hàm cho: {{ $lecturer->full_name }}
        </h2>
    </x-slot>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            @if ($errors->any())
                <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800">
                    <div class="font-medium">Có lỗi xảy ra!</div>
                    <ul class="mt-1 list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif
            <form method="POST" action="{{ route('admin.lecturers.academic-degrees.store', $lecturer->id) }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- THAY THẾ TRƯỜNG degree_name BẰNG DROPDOWN degree_type_id --}}
                    <div>
                        <label for="degree_type_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Loại Bằng cấp/Học vị <span class="text-red-500">*</span></label>
                        <select id="degree_type_id" name="degree_type_id" required
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                            <option value="">-- Chọn loại bằng cấp --</option>
                            @foreach ($degreeTypes as $type) {{-- Biến $degreeTypes được truyền từ controller --}}
                                <option value="{{ $type->id }}" {{ old('degree_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="specialization" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Chuyên ngành <span class="text-red-500">*</span></label>
                        <input id="specialization" type="text" name="specialization" value="{{ old('specialization') }}" required class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                    </div>

                    {{-- Các trường còn lại giữ nguyên --}}
                    <div>
                        <label for="issuing_institution" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Nơi cấp</label>
                        <input id="issuing_institution" type="text" name="issuing_institution" value="{{ old('issuing_institution') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                    </div>
                    <div>
                        <label for="date_issued" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Ngày cấp</label>
                        <input id="date_issued" type="date" name="date_issued" value="{{ old('date_issued') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                    </div>
                    <div class="md:col-span-2">
                        <label for="notes" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Ghi chú</label>
                        <textarea id="notes" name="notes" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ old('notes') }}</textarea>
                    </div>
                </div>
                <div class="flex items-center justify-end mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('lecturers.edit', $lecturer->id) }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">Hủy</a>
                    <button type="submit" class="ms-4 inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Lưu Học vị</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>