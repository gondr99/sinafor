<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/app.css">
    <script src="/js/app.js"></script>
    <title>SINA FOR</title>
    <script src="/js/lang.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <header class="d-flex justify-content-between align-items-center px-4">
        <div id="logo">
            <h2>SINAFOR</h2>
        </div>
        <nav id="menu">
            <ul class="nav">
                @if(auth()->check())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('user/register') ? 'active' : '' }}" href="/user/register">{{  __('menu.register') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/user/logout">{{  __('menu.logout') }}</a>
                    </li>
                    @if(auth()->user()->checkAdmin())
                        <li class="nav-item">
                            <a class="nav-link" href="/admin">{{  __('menu.admin') }}</a>
                        </li>
                     @endif
                @else
                    <li class="nav-item">
                        <a class="nav-link active" href="/user/register">{{  __('menu.register') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/user/login">{{  __('menu.login') }}</a>
                    </li>
                @endif
            </ul>
        </nav>
    </header>
    <div class="container-fluid">
        @yield('content')
    </div>
    @if(session()->has('flash_message'))
        <script>
            Swal.fire({title:'Message from server', text: "{{ session()->pull('flash_message') }}"});
        </script>
    @endif
</body>
</html>
