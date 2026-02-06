<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-beta1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng ký - {{ config('app.name', 'Laravel') }}</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f3f4f6; margin:0; }
        .container { background-color: white; padding: 2rem; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h1 { text-align: center; color: #333; }
        label { display: block; margin-bottom: 0.5rem; font-weight: bold; color: #555;}
        input[type="text"], input[type="email"], input[type="password"] { width: calc(100% - 1.2rem); padding: 0.6rem; margin-bottom: 1rem; border: 1px solid #ddd; border-radius: 0.25rem; }
        button { background-color: #3490dc; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 0.25rem; cursor: pointer; font-size: 1rem; width: 100%; }
        button:hover { background-color: #2779bd; }
        .error-messages { margin-bottom: 1rem; }
        .error-messages ul { list-style-type: none; padding: 0; }
        .error-messages li { color: red; font-size: 0.875rem; margin-bottom: 0.25rem; }
        .login-link { text-align: center; margin-top: 1rem; }
        .login-link a { color: #3490dc; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Đăng Ký Tài Khoản</h1>

        @if ($errors->any())
            <div class="error-messages">
                <strong>Rất tiếc! Có lỗi xảy ra:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf <div>
                <label for="name">Tên của bạn</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
            </div>

            <div style="margin-top: 1rem;">
                <label for="email">Địa chỉ Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
            </div>

            <div style="margin-top: 1rem;">
                <label for="password">Mật khẩu</label>
                <input id="password" type="password" name="password" required autocomplete="new-password">
            </div>

            <div style="margin-top: 1rem;">
                <label for="password_confirmation">Xác nhận Mật khẩu</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
            </div>

            <div style="margin-top: 1.5rem;">
                <button type="submit">
                    Đăng Ký
                </button>
            </div>
        </form>
        <div class="login-link">
            <p>Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập tại đây</a></p> </div>
    </div>
</body>
</html>