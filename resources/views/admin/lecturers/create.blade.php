<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            {{ __('Thêm Giảng viên mới') }}
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

            <form method="POST" action="{{ route('lecturers.store') }}" enctype="multipart/form-data">
                @csrf

                <h3 class="text-lg font-semibold mb-4 border-b pb-2 dark:border-gray-700 dark:text-gray-100">Thông tin cơ bản</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Cột 1 - Thông tin cơ bản --}}
                    <div>
                        <div>
                            <label for="lecturer_code" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Mã Giảng viên') }} <span class="text-red-500">*</span></label>
                            <input id="lecturer_code" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500"
                                   type="text" name="lecturer_code" value="{{ old('lecturer_code') }}" required autofocus />
                        </div>
                        <div class="mt-4">
                            <label for="full_name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Họ tên') }} <span class="text-red-500">*</span></label>
                            <input id="full_name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500"
                                   type="text" name="full_name" value="{{ old('full_name') }}" required />
                        </div>
                        <div class="mt-4">
                            <label for="date_of_birth" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Ngày sinh') }} <span class="text-red-500">*</span></label>
                            <input id="date_of_birth" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500"
                                   type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required />
                        </div>
                        <div class="mt-4">
                            <label for="gender" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Giới tính') }} <span class="text-red-500">*</span></label>
                            <select id="gender" name="gender" required class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500">
                                <option value="" class="text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-700">-- Chọn giới tính --</option>
                                <option value="Nam" {{ old('gender') == 'Nam' ? 'selected' : '' }} class="text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700">Nam</option>
                                <option value="Nữ" {{ old('gender') == 'Nữ' ? 'selected' : '' }} class="text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700">Nữ</option>
                                <option value="Khác" {{ old('gender') == 'Khác' ? 'selected' : '' }} class="text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700">Khác</option>
                            </select>
                        </div>
                        <div class="mt-4">
                            <label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Email') }} <span class="text-red-500">*</span></label>
                            <input id="email" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500"
                                   type="email" name="email" value="{{ old('email') }}" required />
                        </div>
                        <div class="mt-4">
                            <label for="phone_number" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Số điện thoại') }}</label>
                            <input id="phone_number" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500"
                                   type="text" name="phone_number" value="{{ old('phone_number') }}" />
                        </div>
                    </div>

                    {{-- Cột 2 - Thông tin cơ bản --}}
                    <div>
                        <div>
                            <label for="address" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Địa chỉ') }}</label>
                           <textarea id="address" name="address" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500">{{ old('address') }}</textarea>
                        </div>
                        <div class="mt-4">
                            <label for="department_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Khoa/Bộ môn') }}</label>
                            <select id="department_id" name="department_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500">
                                <option value="" class="text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-700">-- Chọn Khoa/Bộ môn --</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }} class="text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700">
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-4">
                            <label for="academic_level" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Trình độ học vấn (Hiện tại)') }} <span class="text-red-500">*</span></label>
                            <select id="academic_level" name="academic_level" required class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500">
                                <option value="" class="text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-700">-- Chọn trình độ --</option>
                                <option value="Cử nhân" {{ old('academic_level') == 'Cử nhân' ? 'selected' : '' }} class="text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700">Cử nhân</option>
                                <option value="Thạc sĩ" {{ old('academic_level') == 'Thạc sĩ' ? 'selected' : '' }} class="text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700">Thạc sĩ</option>
                                <option value="Tiến sĩ" {{ old('academic_level') == 'Tiến sĩ' ? 'selected' : '' }} class="text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700">Tiến sĩ</option>
                                <option value="Phó Giáo sư" {{ old('academic_level') == 'Phó Giáo sư' ? 'selected' : '' }} class="text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700">Phó Giáo sư</option>
                                <option value="Giáo sư" {{ old('academic_level') == 'Giáo sư' ? 'selected' : '' }} class="text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700">Giáo sư</option>
                            </select>
                        </div>
                        <div class="mt-4">
                            <label for="position" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Chức vụ') }}</label>
                            <input id="position" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500"
                                   type="text" name="position" value="{{ old('position') }}" />
                        </div>
                        <div class="mt-4">
                            <label for="avatar" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Ảnh đại diện') }}</label>
                            <input id="avatar" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" type="file" name="avatar" />
                        </div>
                    </div>
                </div>

                <h3 class="text-lg font-semibold mt-8 mb-4 border-b pb-2 dark:border-gray-700 dark:text-gray-100">Học vị/Học hàm (Đầu tiên)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="degree_type_id_0" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Loại Bằng cấp/Học vị</label>
                        <select id="degree_type_id_0" name="academic_degrees[0][degree_type_id]"
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500">
                            <option value="" class="text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-700">-- Chọn loại bằng cấp --</option>
                            @foreach ($degreeTypes as $type)
                                <option value="{{ $type->id }}" {{ old('academic_degrees.0.degree_type_id') == $type->id ? 'selected' : '' }} class="text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700">
                                    {{ $type->name }} {{ $type->abbreviation ? '(' . $type->abbreviation . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="degree_specialization_0" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Chuyên ngành</label>
                        <input id="degree_specialization_0" type="text" name="academic_degrees[0][specialization]" value="{{ old('academic_degrees.0.specialization') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="degree_institution_0" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Nơi cấp</label>
                        <input id="degree_institution_0" type="text" name="academic_degrees[0][issuing_institution]" value="{{ old('academic_degrees.0.issuing_institution') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="degree_date_issued_0" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Ngày cấp</label>
                        <input id="degree_date_issued_0" type="date" name="academic_degrees[0][date_issued]" value="{{ old('academic_degrees.0.date_issued') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500">
                    </div>
                    <div class="md:col-span-2">
                        <label for="degree_notes_0" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Ghi chú (Học vị)</label>
                        <textarea id="degree_notes_0" name="academic_degrees[0][notes]" rows="2" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500">{{ old('academic_degrees.0.notes') }}</textarea>
                    </div>
                </div>

                <h3 class="text-lg font-semibold mt-8 mb-4 border-b pb-2 dark:border-gray-700 dark:text-gray-100">Quá trình công tác (Đầu tiên)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="work_organization" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Tên đơn vị/tổ chức</label>
                        <input id="work_organization" type="text" name="work_histories[0][organization_name]" value="{{ old('work_histories.0.organization_name') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="work_position" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Chức vụ đảm nhiệm</label>
                        <input id="work_position" type="text" name="work_histories[0][position_held]" value="{{ old('work_histories.0.position_held') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="work_start_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Ngày bắt đầu</label>
                        <input id="work_start_date" type="date" name="work_histories[0][start_date]" value="{{ old('work_histories.0.start_date') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="work_end_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Ngày kết thúc (để trống nếu còn làm)</label>
                        <input id="work_end_date" type="date" name="work_histories[0][end_date]" value="{{ old('work_histories.0.end_date') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500">
                    </div>
                    <div class="md:col-span-2">
                        <label for="work_courses_taught" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Môn học phụ trách (nếu có)</label>
                        <textarea id="work_courses_taught" name="work_histories[0][courses_taught]" rows="2" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500">{{ old('work_histories.0.courses_taught') }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label for="work_description" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Mô tả công việc</label>
                        <textarea id="work_description" name="work_histories[0][description]" rows="2" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-500">{{ old('work_histories.0.description') }}</textarea>
                    </div>
                </div>

                {{-- Nút submit --}}
                <div class="flex items-center justify-end mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('lecturers.index') }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                        {{ __('Hủy') }}
                    </a>
                    <button type="submit"
                            class="ms-4 inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        {{ __('Lưu Giảng viên') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>