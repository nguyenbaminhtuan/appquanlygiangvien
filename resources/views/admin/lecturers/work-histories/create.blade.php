<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Thêm Quá trình công tác cho: {{ $lecturer->full_name }}
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
            <form method="POST" action="{{ route('admin.lecturers.work-histories.store', $lecturer->id) }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="organization_name" class="block font-medium text-sm">Tên đơn vị/tổ chức <span class="text-red-500">*</span></label>
                        <input id="organization_name" type="text" name="organization_name" value="{{ old('organization_name') }}" required class="block mt-1 w-full rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="position_held" class="block font-medium text-sm">Chức vụ đảm nhiệm <span class="text-red-500">*</span></label>
                        <input id="position_held" type="text" name="position_held" value="{{ old('position_held') }}" required class="block mt-1 w-full rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="start_date" class="block font-medium text-sm">Ngày bắt đầu <span class="text-red-500">*</span></label>
                        <input id="start_date" type="date" name="start_date" value="{{ old('start_date') }}" required class="block mt-1 w-full rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="end_date" class="block font-medium text-sm">Ngày kết thúc (để trống nếu còn làm)</label>
                        <input id="end_date" type="date" name="end_date" value="{{ old('end_date') }}" class="block mt-1 w-full rounded-md shadow-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label for="courses_taught" class="block font-medium text-sm">Môn học phụ trách (nếu có)</label>
                        <textarea id="courses_taught" name="courses_taught" rows="2" class="block mt-1 w-full rounded-md shadow-sm">{{ old('courses_taught') }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label for="description" class="block font-medium text-sm">Mô tả công việc</label>
                        <textarea id="description" name="description" rows="3" class="block mt-1 w-full rounded-md shadow-sm">{{ old('description') }}</textarea>
                    </div>
                </div>
                <div class="flex items-center justify-end mt-6 pt-6 border-t">
                    <a href="{{ route('lecturers.edit', $lecturer->id) }}" class="underline text-sm">Hủy</a>
                    <button type="submit" class="ms-4 inline-flex items-center px-4 py-2 bg-gray-800 text-white text-xs font-semibold uppercase rounded-md">Lưu Quá trình công tác</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>