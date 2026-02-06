<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException; // Cho việc ném lỗi validation

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login'); // Chúng ta sẽ tạo view này ở bước tiếp theo
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Thử xác thực người dùng
        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            // Nếu xác thực thất bại, ném lỗi ValidationException
            throw ValidationException::withMessages([
                'email' => __('auth.failed'), // Thông báo lỗi chung từ file lang/en/auth.php
            ]);
        }

        // Nếu xác thực thành công, tái tạo session ID để tránh session fixation
        $request->session()->regenerate();

        // Chuyển hướng người dùng đến trang dashboard (hoặc trang họ định truy cập trước đó)
        return redirect()->intended(route('dashboard'));
    }

    /**
     * Destroy an authenticated session (logout).
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout(); // Đăng xuất người dùng

        $request->session()->invalidate(); // Hủy session hiện tại

        $request->session()->regenerateToken(); // Tái tạo CSRF token cho session mới

        return redirect()->route('login'); 
    }
}