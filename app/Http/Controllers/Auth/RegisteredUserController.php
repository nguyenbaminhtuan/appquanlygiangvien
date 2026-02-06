<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules; // Cho quy tắc Password
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse; // Cho kiểu trả về khi redirect

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register'); // Chúng ta sẽ tạo view này ở bước tiếp theo
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class], // Email phải là duy nhất trong bảng users
            'password' => ['required', 'confirmed', Rules\Password::defaults()], // 'confirmed' yêu cầu phải có trường password_confirmation khớp với password
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // (Tùy chọn) Gửi email xác thực ở đây nếu bạn muốn
        // event(new Registered($user)); // Cần import Illuminate\Auth\Events\Registered;

        Auth::login($user); // Tự động đăng nhập cho người dùng vừa đăng ký

        return redirect()->route('dashboard'); // Chuyển hướng đến trang dashboard
    }
}