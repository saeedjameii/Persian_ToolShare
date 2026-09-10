<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'ToolShare — Borrow more. Buy less.')</title>

@vite(['resources/css/style.css', 'resources/js/create-post.js'])

</head>
<body>
<header class="navbar">
    <nav class="container nav-inner">
        <a class="logo" href="{{ route('home') }}">
            <span class="logo-icon">🔧</span>
            ToolShare
        </a>
        @hasSection('nav-links')
            @yield('nav-links')
        @else
            <div class="nav-links">
                <a href="{{ route('home') }}">خانه</a>
                <a href="{{ route('posts.index') }}">مشاهده ابزارها</a>
                <a href="#how">نحوه کار</a>
                @if (auth('api')->check() && auth('api')->user()->hasRole('admin'))
                    <a href="{{ route('users.index') }}">مدیریت کاربران</a>
                @endif
            </div>

        @endif
        <div class="nav-actions">
            @hasSection('header-actions')
                @yield('header-actions')
            @else
                <a class="btn btn-secondary" href="{{ route('login') }}">
                    ورود
                </a>
                <a class="btn btn-primary" href="{{ route('signUp') }}">
                    ثبت‌ نام
                </a>
            @endif
        </div>
    </nav>
</header>
</body>
</html>