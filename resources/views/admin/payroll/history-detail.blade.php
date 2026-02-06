<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Chi tiết Bảng lương: {{ $semester->name }} ({{ $semester->academicYear->name }})
        </h2>
    </x-slot>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <div class="mb-4">
                <a href="{{ route('admin.payroll.history') }}" class="underline text-sm"> &larr; Quay lại Lịch sử Bảng lương</a>
            </div>
            @include('partials.session-messages')

            <div class="overflow-x-auto mt-4 rounded-lg shadow">
                {{-- Bảng chi tiết tương tự như bảng xem trước, nhưng lấy dữ liệu từ $payrollDetails --}}
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                         <tr>
                            <th class="px-3 py-3 text-left text-xs font-medium uppercase">GV</th>
                            <th class="px-3 py-3 text-left text-xs font-medium uppercase">Lớp HP</th>
                            <th class="px-3 py-3 text-center text-xs font-medium uppercase">Số tiết QĐ</th>
                            <th class="px-3 py-3 text-right text-xs font-medium uppercase">Thành tiền</th>
                            <th class="px-3 py-3 text-center text-xs font-medium uppercase">Trạng thái</th>
                            <th class="px-3 py-3 text-left text-xs font-medium uppercase">Hành động</th>
                        </tr>
                    </thead>
                     <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                        @foreach ($payrollDetails as $payment)
                            <tr>
                                <td class="px-3 py-2 whitespace-nowrap text-sm">{{ $payment->lecturer->full_name ?? 'N/A' }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm">{{ $payment->scheduledClass->class_code ?? 'N/A' }}</td>
                                <td class="px-3 py-2 text-center whitespace-nowrap text-sm">{{ number_format($payment->converted_teaching_units, 2) }}</td>
                                <td class="px-3 py-2 text-right whitespace-nowrap text-sm font-semibold">{{ number_format($payment->payment_amount, 0) }}</td>
                                <td class="px-3 py-2 text-center whitespace-nowrap text-sm">
                                    {{-- Hiển thị trạng thái --}}
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($payment->status == 'paid') bg-green-100 text-green-800 @elseif($payment->status == 'approved') bg-green-100 text-green-800 @else bg-green-100 text-green-800 @endif">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium">
                                    {{-- Form cập nhật trạng thái --}}
                                    <form method="POST" action="{{ route('admin.payroll.update-payment-status') }}">
                                        @csrf
                                        <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                                        <select name="status" onchange="this.form.submit()" class="text-xs rounded-md border-gray-300 dark:bg-gray-600 dark:border-gray-500">
                                            <option value="calculated" {{ $payment->status == 'calculated' ? 'selected' : '' }}>Đã tính</option>
                                            <option value="approved" {{ $payment->status == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                                            <option value="paid" {{ $payment->status == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                                            <option value="rejected" {{ $payment->status == 'rejected' ? 'selected' : '' }}>Từ chối</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="px-3 py-2 text-right font-bold text-sm uppercase">Tổng cộng:</td>
                            <td class="px-3 py-2 text-right font-bold text-sm">{{ number_format($grandTotal, 0) }}</td>
                            <td colspan="2"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>