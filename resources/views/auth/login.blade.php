<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập - {{ config('app.name', 'Laravel') }}</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f3f4f6; margin: 0; }
        .container { background-color: white; padding: 2rem; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h1 { text-align: center; color: #333; }
        label { display: block; margin-bottom: 0.5rem; font-weight: bold; color: #555; }
        input[type="email"], input[type="password"] { width: calc(100% - 1.2rem); padding: 0.6rem; margin-bottom: 1rem; border: 1px solid #ddd; border-radius: 0.25rem; }
        input[type="checkbox"] { margin-right: 0.5rem; }
        button { background-color: #3490dc; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 0.25rem; cursor: pointer; font-size: 1rem; width: 100%; }
        button:hover { background-color: #2779bd; }
        .error-messages { margin-bottom: 1rem; }
        .error-messages ul { list-style-type: none; padding: 0; } /* Sửa lại từ .error-messages strong thành ul */
        .error-messages li, .error-messages .single-error { color: red; font-size: 0.875rem; margin-bottom: 0.25rem; } /* Thêm .single-error */
        .links-container { display: flex; justify-content: space-between; align-items: center; margin-top: 1rem; font-size: 0.875rem;}
        .links-container a { color: #3490dc; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Đăng Nhập</h1>

        @if ($errors->any())
            <div class="error-messages">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <label for="email">Địa chỉ Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            </div>

            <div style="margin-top: 1rem;">
                <label for="password">Mật khẩu</label>
                <input id="password" type="password" name="password" required autocomplete="current-password">
            </div>

            <div style="margin-top: 1rem;">
                <label for="remember_me" style="display: inline-flex; align-items: center; font-weight: normal;">
                    <input id="remember_me" type="checkbox" name="remember">
                    <span style="margin-left: 0.5rem;">Ghi nhớ đăng nhập</span>
                </label>
            </div>

            <div style="margin-top: 1.5rem;">
                <button type="submit">
                    Đăng Nhập
                </button>
            </div>

            <div class="links-container">
                @if (Route::has('password.request')) <a href="{{ route('password.request') }}">
                        Quên mật khẩu?
                    </a>
                @else
                    <span>&nbsp;</span> @endif

                @if (Route::has('register'))
                    <a href="{{ route('register') }}">
                        Chưa có tài khoản? Đăng ký
                    </a>
                @endif
            </div>
        </form>
    </div>
</body>
</html>