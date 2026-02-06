<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            {{ __('Chỉnh sửa thông tin Giảng viên: ') }} {{ $lecturer->full_name }}
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
            @if (session('success'))
                  <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-900 dark:text-green-300" role="alert">
                      {{ session('success') }}
                  </div>
              @endif
              @if (session('error'))
                  <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-900 dark:text-red-300" role="alert">
                      {{ session('error') }}
                  </div>
              @endif


            <form method="POST" action="{{ route('lecturers.update', $lecturer->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <h3 class="text-lg font-semibold mb-4 border-b pb-2 dark:border-gray-700 dark:text-gray-100">Thông tin cơ bản</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Cột 1 - Thông tin cơ bản --}}
                    <div>
                        <div>
                            <label for="lecturer_code" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Mã Giảng viên') }} <span class="text-red-500">*</span></label>
                            <input id="lecturer_code" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500"
                                   type="text" name="lecturer_code" value="{{ old('lecturer_code', $lecturer->lecturer_code) }}" required autofocus />
                        </div>
                        <div class="mt-4">
                            <label for="full_name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Họ tên') }} <span class="text-red-500">*</span></label>
                            <input id="full_name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500"
                                   type="text" name="full_name" value="{{ old('full_name', $lecturer->full_name) }}" required />
                        </div>
                        <div class="mt-4">
                            <label for="date_of_birth" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Ngày sinh') }} <span class="text-red-500">*</span></label>
                            <input id="date_of_birth" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500"
                                   type="date" name="date_of_birth" value="{{ old('date_of_birth', $lecturer->date_of_birth ? $lecturer->date_of_birth->format('Y-m-d') : '') }}" required />
                        </div>
                        <div class="mt-4">
                            <label for="gender" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Giới tính') }} <span class="text-red-500">*</span></label>
                            <select id="gender" name="gender" required class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500">
                                <option value="Nam" {{ old('gender', $lecturer->gender) == 'Nam' ? 'selected' : '' }} class="text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700">Nam</option>
                                <option value="Nữ" {{ old('gender', $lecturer->gender) == 'Nữ' ? 'selected' : '' }} class="text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700">Nữ</option>
                                <option value="Khác" {{ old('gender', $lecturer->gender) == 'Khác' ? 'selected' : '' }} class="text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700">Khác</option>
                            </select>
                        </div>
                        <div class="mt-4">
                            <label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Email') }} <span class="text-red-500">*</span></label>
                            <input id="email" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500"
                                   type="email" name="email" value="{{ old('email', $lecturer->email) }}" required />
                        </div>
                        <div class="mt-4">
                            <label for="phone_number" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Số điện thoại') }}</label>
                            <input id="phone_number" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500"
                                   type="text" name="phone_number" value="{{ old('phone_number', $lecturer->phone_number) }}" />
                        </div>
                    </div>

                    {{-- Cột 2 - Thông tin cơ bản --}}
                    <div>
                        <div>
                            <label for="address" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Địa chỉ') }}</label>
                            <textarea id="address" name="address" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500">{{ old('address', $lecturer->address) }}</textarea>
                        </div>
                        <div class="mt-4">
                            <label for="department_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Khoa/Bộ môn') }}</label>
                            <select id="department_id" name="department_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500">
                                <option value="" class="text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-700">-- Chọn Khoa/Bộ môn --</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id', $lecturer->department_id) == $department->id ? 'selected' : '' }} class="text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700">
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-4">
                            <label for="academic_level" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Trình độ học vấn (Hiện tại)') }} <span class="text-red-500">*</span></label>
                            <select id="academic_level" name="academic_level" required class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500">
                                <option value="" class="text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-700">-- Chọn trình độ --</option>
                                <option value="Cử nhân" {{ old('academic_level', $lecturer->academic_level) == 'Cử nhân' ? 'selected' : '' }} class="text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700">Cử nhân</option>
                                <option value="Thạc sĩ" {{ old('academic_level', $lecturer->academic_level) == 'Thạc sĩ' ? 'selected' : '' }} class="text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700">Thạc sĩ</option>
                                <option value="Tiến sĩ" {{ old('academic_level', $lecturer->academic_level) == 'Tiến sĩ' ? 'selected' : '' }} class="text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700">Tiến sĩ</option>
                                <option value="Phó Giáo sư" {{ old('academic_level', $lecturer->academic_level) == 'Phó Giáo sư' ? 'selected' : '' }} class="text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700">Phó Giáo sư</option>
                                <option value="Giáo sư" {{ old('academic_level', $lecturer->academic_level) == 'Giáo sư' ? 'selected' : '' }} class="text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700">Giáo sư</option>
                            </select>
                        </div>
                        <div class="mt-4">
                            <label for="position" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Chức vụ') }}</label>
                            <input id="position" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500"
                                   type="text" name="position" value="{{ old('position', $lecturer->position) }}" />
                        </div>
                        <div class="mt-4">
                            <label for="avatar" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Ảnh đại diện (Để trống nếu không muốn thay đổi)') }}</label>
                            @if ($lecturer->avatar)
                                <img src="{{ Storage::url($lecturer->avatar) }}" alt="{{ $lecturer->full_name }}" class="mt-2 w-24 h-24 object-cover rounded-md">
                            @endif
                            <input id="avatar" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" type="file" name="avatar" />
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('lecturers.show', $lecturer->id) }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                        {{ __('Hủy') }}
                    </a>
                    <button type="submit"
                            class="ms-4 inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        {{ __('Cập nhật Giảng viên') }}
                    </button>
                </div>
            </form>

            {{-- PHẦN QUẢN LÝ HỌC VỊ/HỌC HÀM --}}
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold dark:text-gray-100">Học vị/Học hàm</h3>
                    <a href="{{ route('admin.lecturers.academic-degrees.create', $lecturer->id) }}"
                       class="inline-flex items-center px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-medium rounded-md">
                        Thêm mới Học vị
                    </a>
                </div>
                @if($lecturer->academicDegrees->isNotEmpty())
                    <ul class="space-y-2">
                        @foreach($lecturer->academicDegrees as $degree)
                            <li class="p-3 border rounded-md dark:border-gray-600 flex justify-between items-center">
                                <div>
                                    <strong class="dark:text-white">{{ $degree->degreeType->name ?? 'Không rõ Loại bằng cấp' }}</strong>
                                    @if($degree->specialization)
                                        <span class="dark:text-gray-300">- {{ $degree->specialization }}</span>
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400">(Chưa có chuyên ngành)</span>
                                    @endif
                                    <br><small class="text-gray-600 dark:text-gray-400">Nơi cấp: {{ $degree->issuing_institution ?? 'N/A' }} - Ngày cấp: {{ $degree->date_issued ? \Carbon\Carbon::parse($degree->date_issued)->format('d/m/Y') : 'N/A' }}</small>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.lecturers.academic-degrees.edit', ['lecturer' => $lecturer->id, 'degree' => $degree->id]) }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Sửa</a>
                                    <form action="{{ route('admin.lecturers.academic-degrees.destroy', ['lecturer' => $lecturer->id, 'degree' => $degree->id]) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa học vị này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 dark:text-red-400 hover:underline">Xóa</button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-600 dark:text-gray-400">Chưa có thông tin học vị/học hàm.</p>
                @endif
            </div>

            {{-- PHẦN QUẢN LÝ QUÁ TRÌNH CÔNG TÁC --}}
             <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold dark:text-gray-100">Quá trình công tác/giảng dạy</h3>
                    <a href="{{ route('admin.lecturers.work-histories.create', $lecturer->id) }}"
                       class="inline-flex items-center px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-medium rounded-md">
                        Thêm QT Công tác
                    </a>
                </div>
                @if($lecturer->workHistories->isNotEmpty())
                    <ul class="space-y-3">
                        @foreach($lecturer->workHistories as $history)
                            <li class="p-3 border rounded-md dark:border-gray-600">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <strong class="dark:text-white">{{ $history->position_held }}</strong> <span class="dark:text-gray-300">tại</span> <strong class="dark:text-white">{{ $history->organization_name }}</strong>
                                        <br><small class="text-gray-600 dark:text-gray-400">
                                            Từ {{ $history->start_date ? \Carbon\Carbon::parse($history->start_date)->format('d/m/Y') : 'N/A' }}
                                        @if($history->end_date) đến {{ \Carbon\Carbon::parse($history->end_date)->format('d/m/Y') }} @else đến nay @endif
                                        </small>
                                        @if($history->courses_taught)<br><small class="text-gray-600 dark:text-gray-400">Môn dạy: {{ $history->courses_taught }}</small>@endif
                                    </div>
                                    <div class="flex space-x-2">
                                         <a href="{{ route('admin.lecturers.work-histories.edit', ['lecturer' => $lecturer->id, 'history' => $history->id]) }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Sửa</a>
                                         <form action="{{ route('admin.lecturers.work-histories.destroy', ['lecturer' => $lecturer->id, 'history' => $history->id]) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa mục này?');">
                                             @csrf
                                             @method('DELETE')
                                             <button type="submit" class="text-xs text-red-600 dark:text-red-400 hover:underline">Xóa</button>
                                         </form>
                                    </div>
                                </div>
                                @if($history->description)<p class="text-sm text-gray-600 dark:text-gray-400 mt-1"><em>Mô tả: {{ $history->description }}</em></p>@endif
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