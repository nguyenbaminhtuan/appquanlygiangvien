<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $header_title ?? config('app.name', 'EduManager') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased bg-slate-100 text-slate-800">
        
        <div class="min-h-screen flex flex-col md:flex-row">
            
            {{-- ======================== 1. SIDEBAR (MÀU BẠC) ======================== --}}
            <aside class="w-full md:w-64 bg-slate-50 border-r border-slate-200 flex-shrink-0 hidden md:flex flex-col h-screen sticky top-0 z-20 shadow-[4px_0_24px_rgba(0,0,0,0.02)]">
                
                {{-- Logo Area --}}
                <div class="h-16 flex items-center justify-center border-b border-slate-200 bg-slate-100/50 backdrop-blur-sm">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 font-bold text-xl text-indigo-600 tracking-tight no-underline">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <span>EduManager</span>
                    </a>
                </div>

                {{-- Menu List --}}
                <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1 custom-scrollbar">
                    
                    <p class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 mt-2">Tổng quan</p>
                    
                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-white text-indigo-600 shadow-sm ring-1 ring-slate-200' : 'text-slate-600 hover:bg-slate-200/60 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-indigo-500' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        Trang chủ
                    </a>

                    <p class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 mt-6">Quản lý Đào tạo</p>

                    <a href="{{ route('departments.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('departments.*') ? 'bg-white text-indigo-600 shadow-sm ring-1 ring-slate-200' : 'text-slate-600 hover:bg-slate-200/60 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('departments.*') ? 'text-indigo-500' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Quản lý Khoa
                    </a>

                    <a href="{{ route('lecturers.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('lecturers.*') ? 'bg-white text-indigo-600 shadow-sm ring-1 ring-slate-200' : 'text-slate-600 hover:bg-slate-200/60 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('lecturers.*') ? 'text-indigo-500' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Quản lý Giảng viên
                    </a>

                    <a href="{{ route('subjects.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('subjects.*') ? 'bg-white text-indigo-600 shadow-sm ring-1 ring-slate-200' : 'text-slate-600 hover:bg-slate-200/60 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('subjects.*') ? 'text-indigo-500' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        Quản lý Học phần
                    </a>

                    {{-- Dropdown Menu --}}
                    <div x-data="{ open: false }" class="space-y-1">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg text-slate-600 hover:bg-slate-200/60 hover:text-slate-900 transition-colors">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                Danh mục khác
                            </div>
                            <svg :class="{'rotate-180': open}" class="w-4 h-4 text-slate-400 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" class="pl-11 space-y-1" style="display: none;">
                            <a href="{{ route('degree-types.index') }}" class="block px-3 py-2 text-sm text-slate-500 hover:text-indigo-600 transition-colors">QL Bằng cấp</a>
                            <a href="{{ route('academic-years.index') }}" class="block px-3 py-2 text-sm text-slate-500 hover:text-indigo-600 transition-colors">QL Năm học</a>
                            <a href="{{ route('semesters.index') }}" class="block px-3 py-2 text-sm text-slate-500 hover:text-indigo-600 transition-colors">QL Kì học</a>
                        </div>
                    </div>

                    <p class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 mt-6">Lớp & Phân công</p>

                    <a href="{{ route('admin.course-offerings.open-batch.create') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.course-offerings.*') ? 'bg-white text-indigo-600 shadow-sm ring-1 ring-slate-200' : 'text-slate-600 hover:bg-slate-200/60 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.course-offerings.*') ? 'text-indigo-500' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        Mở Lớp học phần
                    </a>
                    <a href="{{ route('scheduled-classes.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('scheduled-classes.*') ? 'bg-white text-indigo-600 shadow-sm ring-1 ring-slate-200' : 'text-slate-600 hover:bg-slate-200/60 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('scheduled-classes.*') ? 'text-indigo-500' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        QL Lớp học phần
                    </a>
                    <a href="{{ route('admin.assignments.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.assignments.*') ? 'bg-white text-indigo-600 shadow-sm ring-1 ring-slate-200' : 'text-slate-600 hover:bg-slate-200/60 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.assignments.*') ? 'text-indigo-500' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Phân công Giảng dạy
                    </a>
                    <a href="{{ route('admin.reports.subject-class-statistics') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.reports.subject-class-statistics') ? 'bg-white text-indigo-600 shadow-sm ring-1 ring-slate-200' : 'text-slate-600 hover:bg-slate-200/60 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.reports.subject-class-statistics') ? 'text-indigo-500' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"></path></svg>
                        Thống kê
                    </a>

                    <p class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 mt-6">Tài chính</p>

                    <div x-data="{ open: false }" class="space-y-1">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg text-slate-600 hover:bg-slate-200/60 hover:text-slate-900 transition-colors">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Cấu hình Lương
                            </div>
                            <svg :class="{'rotate-180': open}" class="w-4 h-4 text-slate-400 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" class="pl-11 space-y-1" style="display: none;">
                            <a href="{{ route('lecturer-pay-rates.index') }}" class="block px-3 py-2 text-sm text-slate-500 hover:text-indigo-600 transition-colors">Hệ số lương GV</a>
                            <a href="{{ route('class-size-coefficients.index') }}" class="block px-3 py-2 text-sm text-slate-500 hover:text-indigo-600 transition-colors">Hệ số Sĩ số</a>
                        </div>
                    </div>

                    <a href="{{ route('admin.payroll.generate-form') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.payroll.generate-form') ? 'bg-white text-indigo-600 shadow-sm ring-1 ring-slate-200' : 'text-slate-600 hover:bg-slate-200/60 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.payroll.generate-form') ? 'text-indigo-500' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        Tính tiền dạy
                    </a>
                    <a href="{{ route('admin.payroll.history') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.payroll.history') ? 'bg-white text-indigo-600 shadow-sm ring-1 ring-slate-200' : 'text-slate-600 hover:bg-slate-200/60 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.payroll.history') ? 'text-indigo-500' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Lịch sử Lương
                    </a>
                    <a href="{{ route('admin.reports.payroll') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.reports.payroll') ? 'bg-white text-indigo-600 shadow-sm ring-1 ring-slate-200' : 'text-slate-600 hover:bg-slate-200/60 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.reports.payroll') ? 'text-indigo-500' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Báo cáo Lương
                    </a>

                </div>

                {{-- Nút Đăng xuất --}}
                <div class="p-4 border-t border-slate-200 bg-slate-50">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center w-full px-3 py-2.5 text-sm font-medium text-red-600 rounded-lg hover:bg-red-50 hover:text-red-700 transition-colors">
                            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Đăng xuất
                        </button>
                    </form>
                </div>
            </aside>

            {{-- ======================== 2. MAIN CONTENT (BÊN PHẢI) ======================== --}}
            <div class="flex-1 flex flex-col min-h-screen">
                
                {{-- 2.1 HEADER (MÀU BẠC) --}}
                <header class="bg-slate-50 border-b border-slate-200 h-16 flex items-center justify-between px-6 sticky top-0 z-10 shadow-sm/50">
                    
                    {{-- Tiêu đề trang --}}
                    <div class="flex-1 min-w-0">
                        @if (isset($header))
                            <h2 class="font-bold text-xl text-slate-800 leading-tight truncate">
                                {{ $header }}
                            </h2>
                        @else
                            <h2 class="font-bold text-xl text-slate-800 leading-tight truncate">
                                {{ $header_title ?? 'Dashboard' }}
                            </h2>
                        @endif
                    </div>

                    {{-- User Dropdown --}}
                    <div class="flex items-center gap-4">
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = ! open" class="flex items-center gap-3 text-sm font-medium text-slate-700 hover:text-indigo-600 transition focus:outline-none">
                                <div class="text-right hidden sm:block">
                                    <div class="text-sm font-bold text-slate-800">{{ Auth::user()->name ?? 'Admin' }}</div>
                                    <div class="text-xs text-slate-500">{{ Auth::user()->email ?? '' }}</div>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-indigo-600 font-bold uppercase text-lg border border-slate-200 shadow-sm">
                                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                                </div>
                            </button>

                            {{-- Dropdown Menu --}}
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl py-2 border border-slate-100 z-50 ring-1 ring-black ring-opacity-5" style="display: none;" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95">
                                <div class="px-4 py-2 border-b border-slate-100 text-xs text-slate-400 font-semibold uppercase">
                                    Quản lý tài khoản
                                </div>
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors">
                                        Đăng xuất
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                {{-- 2.2 NỘI DUNG CHÍNH (NỀN XÁM ĐẬM HƠN CHÚT ĐỂ NỔI BẬT NỘI DUNG) --}}
                <main class="flex-1 p-6 bg-slate-100 overflow-x-hidden overflow-y-auto">
                    {{-- Khu vực hiển thị thông báo (Alert) nếu có --}}
                    @if(session('success'))
                        <div class="mb-4 p-4 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>