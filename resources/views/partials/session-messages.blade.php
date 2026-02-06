{{-- resources/views/partials/session-messages.blade.php --}}
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
@if ($errors->any() && !request()->isMethod('get')) {{-- Chỉ hiển thị lỗi validation nếu không phải GET request (tránh hiển thị khi tải trang edit lần đầu) --}}
    <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-900 dark:text-red-300" role="alert">
        <div class="font-medium">{{ __('Rất tiếc! Có lỗi xảy ra.') }}</div>
        <ul class="mt-1 list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif