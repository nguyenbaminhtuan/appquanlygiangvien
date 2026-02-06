<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            {{ __('Chi tiết Giảng viên: ') }} {{ $lecturer->full_name }}
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">

            {{-- Nút hành động --}}
            <div class="flex justify-end space-x-3">
                <a href="{{ route('lecturers.edit', $lecturer->id) }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs uppercase tracking-widest rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('Sửa thông tin') }}
                </a>
                <a href="{{ route('lecturers.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white font-semibold text-xs uppercase tracking-widest rounded-md focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('Trở lại Danh sách') }}
                </a>
            </div>

            {{-- Thông tin cơ bản --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
                {{-- Ảnh đại diện --}}
                <div class="md:col-span-1">
                    @if ($lecturer->avatar)
                        <img src="{{ asset('storage/' . $lecturer->avatar) }}" alt="{{ $lecturer->full_name }}" class="w-48 h-48 object-cover rounded-lg shadow-md mx-auto md:mx-0">
                    @else
                        <div class="w-48 h-48 bg-gray-200 dark:bg-gray-700 rounded-lg shadow-md flex items-center justify-center text-gray-500 dark:text-gray-400 mx-auto md:mx-0">
                            {{ __('Không có ảnh') }}
                        </div>
                    @endif
                </div>

                {{-- Chi tiết --}}
                <div class="md:col-span-2 space-y-2">
                    <p><strong>{{ __('Mã GV:') }}</strong> {{ $lecturer->lecturer_code }}</p>
                    <p><strong>{{ __('Họ tên:') }}</strong> {{ $lecturer->full_name }}</p>
                    <p><strong>{{ __('Ngày sinh:') }}</strong> {{ $lecturer->date_of_birth ? \Carbon\Carbon::parse($lecturer->date_of_birth)->format('d/m/Y') : 'N/A' }}</p>
                    <p><strong>{{ __('Giới tính:') }}</strong> {{ $lecturer->gender }}</p>
                    <p><strong>{{ __('Email:') }}</strong> {{ $lecturer->email }}</p>
                    <p><strong>{{ __('Số điện thoại:') }}</strong> {{ $lecturer->phone_number ?? 'N/A' }}</p>
                    <p><strong>{{ __('Địa chỉ:') }}</strong> {{ $lecturer->address ?? 'N/A' }}</p>
                    <p><strong>{{ __('Khoa/Bộ môn:') }}</strong> {{ $lecturer->department->name ?? 'N/A' }}</p>
                    <p><strong>{{ __('Trình độ học vấn (hiện tại):') }}</strong> {{ $lecturer->academic_level }}</p>
                    <p><strong>{{ __('Chức vụ:') }}</strong> {{ $lecturer->position ?? 'N/A' }}</p>
                    @if($lecturer->user)
                    <p><strong>{{ __('Tài khoản hệ thống:') }}</strong> {{ $lecturer->user->name }} ({{ $lecturer->user->email }})</p>
                    @endif
                </div>
            </div>

            {{-- Hiển thị Học vị/Học hàm từ bảng academic_degrees --}}
            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <h4 class="text-lg font-semibold mb-3">{{ __('Học vị/Học hàm') }}</h4>
                @if($lecturer->academicDegrees->isNotEmpty())
                    <ul class="list-disc list-inside space-y-2">
                        @foreach($lecturer->academicDegrees as $degree)
                            <li>
                                {{-- SỬA ĐỔI CHÍNH Ở ĐÂY --}}
                                <strong>{{ $degree->degreeType->name ?? 'Không rõ Loại bằng cấp' }}</strong>
                                @if($degree->specialization)
                                    - {{ $degree->specialization }}
                                @else
                                    (Chưa có chuyên ngành)
                                @endif
                                {{-- KẾT THÚC SỬA ĐỔI --}}
                                <br> <small class="text-gray-600 dark:text-gray-400">
                                    Nơi cấp: {{ $degree->issuing_institution ?? 'N/A' }}
                                    - Ngày cấp: {{ $degree->date_issued ? \Carbon\Carbon::parse($degree->date_issued)->format('d/m/Y') : 'N/A' }}
                                </small>
                                @if($degree->notes)
                                    <br> <small class="text-gray-600 dark:text-gray-400"><em>Ghi chú: {{ $degree->notes }}</em></small>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-600 dark:text-gray-400">Chưa có thông tin học vị/học hàm.</p>
                @endif
            </div>

            {{-- Hiển thị Quá trình công tác/giảng dạy từ bảng work_histories --}}
            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <h4 class="text-lg font-semibold mb-3">{{ __('Quá trình công tác/giảng dạy') }}</h4>
                @if($lecturer->workHistories->isNotEmpty())
                    <ul class="space-y-3">
                        @foreach($lecturer->workHistories as $history)
                            <li class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700 last:border-b-0 last:pb-0 last:mb-0">
                                <strong>{{ $history->position_held }}</strong> tại {{ $history->organization_name }}
                                <br><small class="text-gray-600 dark:text-gray-400">
                                    Từ {{ $history->start_date ? \Carbon\Carbon::parse($history->start_date)->format('d/m/Y') : 'N/A' }}
                                @if($history->end_date)
                                    đến {{ \Carbon\Carbon::parse($history->end_date)->format('d/m/Y') }}
                                @else
                                    đến nay
                                @endif
                                </small>
                                @if($history->courses_taught) <br><small class="text-gray-600 dark:text-gray-400">Môn học phụ trách: {{ $history->courses_taught }}</small> @endif
                                @if($history->description) <br><small class="text-gray-600 dark:text-gray-400"><em>Mô tả: {{ $history->description }}</em></small> @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-600 dark:text-gray-400">Chưa có thông tin quá trình công tác.</p>
                @endif
            </div>

        </div>
    </div>
</x-admin-layout>