<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            {{ __('Quản lý Hệ số lương Giảng viên') }}
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <div class="mb-4">
                <a href="{{ route('lecturer-pay-rates.create') }}" class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 border rounded-md font-semibold text-xs text-white uppercase">
                    {{ __('Thêm mới Hệ số') }}
                </a>
            </div>

            @include('partials.session-messages') {{-- Để hiển thị success/error messages --}}

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Trình độ/Chức danh</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Hệ số</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Ngày hiệu lực</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Ghi chú</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($lecturerPayRates as $rate)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $rate->academic_level_or_title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ number_format($rate->coefficient, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $rate->effective_date->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-sm max-w-xs truncate">{{ Str::limit($rate->notes, 50) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('lecturer-pay-rates.edit', $rate->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Sửa</a>
                                    <form action="{{ route('lecturer-pay-rates.destroy', $rate->id) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn xóa?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm">Không có hệ số lương nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $lecturerPayRates->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>