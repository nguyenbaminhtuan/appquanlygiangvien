<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            {{ __('Lịch sử Bảng lương đã chốt') }}
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            @include('partials.session-messages')
            {{-- Form lọc theo Năm học --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Kì học (Năm học)</th>
                            <th class="px-6 py-3 text-center text-xs font-medium uppercase">Số GV</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase">Tổng tiền</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Ngày tính gần nhất</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                        @forelse ($payrollSummaries as $summary)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $summary->semester->name ?? 'N/A' }} ({{ $summary->semester->academicYear->name ?? 'N/A' }})</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">{{ $summary->total_lecturers }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right">{{ number_format($summary->total_payment, 0) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ \Carbon\Carbon::parse($summary->last_calculation_date)->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.payroll.history.show', $summary->semester_id) }}" class="text-indigo-600 hover:text-indigo-900">Xem chi tiết</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm">Không có dữ liệu bảng lương nào đã được lưu.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $payrollSummaries->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>