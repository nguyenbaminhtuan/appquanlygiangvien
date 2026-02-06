<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            {{ __('Bảng điều khiển Trung tâm') }}
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- BLOCK 1: BANNER CHÀO MỪNG (Gradient đẹp mắt) --}}
            <div class="relative overflow-hidden bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-2xl shadow-xl">
                {{-- Họa tiết nền trang trí --}}
                <div class="absolute top-0 left-0 w-full h-full opacity-10">
                    <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                                <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#grid)" />
                    </svg>
                </div>
                
                <div class="relative p-8 md:p-10 text-white flex flex-col md:flex-row items-start md:items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight mb-2">
                            Xin chào, {{ Auth::user()->name }}! 👋
                        </h1>
                        <p class="text-indigo-100 text-lg max-w-2xl">
                            Chúc bạn một ngày làm việc hiệu quả. Đây là tổng quan tình hình đào tạo của hệ thống.
                        </p>
                    </div>
                    
                    {{-- Thông tin Kì học hiện tại (Nổi bật) --}}
                    @if(isset($currentSemester) && $currentSemester)
                        <div class="mt-6 md:mt-0 bg-white/20 backdrop-blur-md rounded-xl p-4 border border-white/30 shadow-sm">
                            <p class="text-xs uppercase tracking-wider font-semibold text-indigo-100 mb-1">Kì học hiện tại</p>
                            <div class="flex items-center gap-3">
                                <div class="bg-white text-indigo-600 rounded-lg p-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold text-xl">{{ $currentSemester->name }}</p>
                                    <p class="text-sm text-indigo-50">{{ $currentSemester->academicYear->name }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="mt-6 md:mt-0 bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                            <span class="text-sm">Chưa thiết lập kì học hiện tại</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- BLOCK 2: CÁC THẺ THỐNG KÊ (STAT CARDS) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                {{-- Card 1: Giảng viên --}}
                <div class="group bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-100 dark:border-gray-700 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tổng Giảng viên</p>
                            <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-2">
                                {{ $totalLecturers ?? '0' }}
                            </h3>
                        </div>
                        <div class="p-3 bg-blue-50 dark:bg-blue-900/30 rounded-xl text-blue-600 dark:text-blue-400 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm text-gray-500">
                        <span class="text-green-500 font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            Hoạt động
                        </span>
                        <span class="ml-2">trong hệ thống</span>
                    </div>
                </div>

                {{-- Card 2: Khoa/Bộ môn --}}
                <div class="group bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-100 dark:border-gray-700 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Khoa & Bộ môn</p>
                            <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-2">
                                {{ $totalDepartments ?? '0' }}
                            </h3>
                        </div>
                        <div class="p-3 bg-purple-50 dark:bg-purple-900/30 rounded-xl text-purple-600 dark:text-purple-400 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm text-gray-500">
                        <span class="text-gray-400">Đơn vị quản lý</span>
                    </div>
                </div>

                {{-- Card 3: Học phần --}}
                <div class="group bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-100 dark:border-gray-700 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tổng Học phần</p>
                            <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-2">
                                {{ $totalSubjects ?? '0' }}
                            </h3>
                        </div>
                        <div class="p-3 bg-emerald-50 dark:bg-emerald-900/30 rounded-xl text-emerald-600 dark:text-emerald-400 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm text-gray-500">
                        <span class="text-gray-400">Môn học trong CTĐT</span>
                    </div>
                </div>

                {{-- Card 4: Lớp học phần --}}
                <div class="group bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-100 dark:border-gray-700 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Lớp đang mở</p>
                            <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-2">
                                {{ $totalScheduledClassesInCurrentSemester ?? '0' }}
                            </h3>
                        </div>
                        <div class="p-3 bg-amber-50 dark:bg-amber-900/30 rounded-xl text-amber-600 dark:text-amber-400 group-hover:bg-amber-600 group-hover:text-white transition-colors duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm text-gray-500">
                        <span class="text-amber-500 font-medium">Kì hiện tại</span>
                        <span class="ml-2">đang giảng dạy</span>
                    </div>
                </div>

            </div>

            {{-- BLOCK 3: KHU VỰC BIỂU ĐỒ (CHARTS) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Biểu đồ 1: Thống kê Lớp học phần theo tháng (Chiếm 2 phần) --}}
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-slate-800">Tần suất mở lớp (Năm {{ date('Y') }})</h3>
                        <span class="text-xs font-medium px-2 py-1 bg-indigo-50 text-indigo-600 rounded-md">Dữ liệu thực tế</span>
                    </div>
                    {{-- Nơi vẽ biểu đồ --}}
                    <div id="chart-classes"></div>
                </div>

                {{-- Biểu đồ 2: Trình độ Giảng viên (Chiếm 1 phần) --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Cơ cấu Trình độ GV</h3>
                    {{-- Nơi vẽ biểu đồ --}}
                    <div id="chart-levels" class="flex justify-center"></div>
                </div>
            </div>

            {{-- BLOCK 4: LỐI TẮT NHANH (Giữ nguyên hoặc chỉnh sửa tùy ý) --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Lối tắt nhanh</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('admin.course-offerings.open-batch.create') }}" class="flex items-center p-3 rounded-xl bg-slate-50 hover:bg-indigo-50 hover:border-indigo-200 border border-transparent transition-all group">
                        <div class="p-2 bg-white rounded-lg text-indigo-600 shadow-sm group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-bold text-slate-700">Mở lớp mới</p>
                        </div>
                    </a>
                    {{-- Các nút tắt khác... --}}
                </div>
            </div>

        </div>
    </div>

    {{-- SCRIPTS VẼ BIỂU ĐỒ --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // --- BIỂU ĐỒ 1: CỘT (CLASSES PER MONTH) ---
            var optionsClasses = {
                series: [{
                    name: 'Số lớp mở',
                    data: @json($monthlyData) // Dữ liệu từ Controller: [5, 10, 0, ...]
                }],
                chart: {
                    type: 'bar',
                    height: 320,
                    fontFamily: 'Figtree, sans-serif',
                    toolbar: { show: false }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        columnWidth: '50%',
                    }
                },
                dataLabels: { enabled: false },
                stroke: { show: true, width: 2, colors: ['transparent'] },
                xaxis: {
                    categories: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: { title: { text: 'Số lượng lớp' } },
                fill: { opacity: 1, colors: ['#6366f1'] }, // Màu Indigo
                tooltip: {
                    y: { formatter: function (val) { return val + " lớp" } }
                }
            };
            var chartClasses = new ApexCharts(document.querySelector("#chart-classes"), optionsClasses);
            chartClasses.render();


            // --- BIỂU ĐỒ 2: DONUT (LECTURER LEVELS) ---
            var labels = @json($levelLabels); // ['Thạc sĩ', 'Tiến sĩ']
            var series = @json($levelData);   // [10, 5]

            // Nếu không có dữ liệu thì hiển thị dummy để test cho đẹp
            if(series.length === 0) {
                labels = ['Chưa có dữ liệu'];
                series = [1];
            }

            var optionsLevels = {
                series: series,
                labels: labels,
                chart: {
                    type: 'donut',
                    height: 320,
                    fontFamily: 'Figtree, sans-serif',
                },
                colors: ['#3b82f6', '#8b5cf6', '#10b981', '#f59e0b'], // Blue, Purple, Green, Amber
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Tổng số',
                                    fontSize: '14px',
                                    fontWeight: 600,
                                    color: '#64748b'
                                }
                            }
                        }
                    }
                },
                legend: {
                    position: 'bottom'
                },
                dataLabels: { enabled: false }
            };
            var chartLevels = new ApexCharts(document.querySelector("#chart-levels"), optionsLevels);
            chartLevels.render();

        });
    </script>
</x-admin-layout>