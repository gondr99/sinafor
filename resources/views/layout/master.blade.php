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
    <script>
        window.adminName = `{{env('ADMIN_NAME')}}`;
        window.expertName = `{{env('EXPERT_NAME')}}`;
        window.managerName = `{{env('MANAGER_NAME')}}`;
        window.verified = `{{env('VERIFIED_NAME')}}`
    </script>
</head>
<body>
<header class="d-flex justify-content-between align-items-center px-4">
    <div id="logo">
        <a href="/main"><img src="/image/logo-sinafor.png" alt="logo image"></a>
    </div>
    <nav id="menu">
        <ul class="nav nav-fill">
            @if(auth()->check())
                <li class="nav-item">
                    <a class="nav-link" href="/user/logout">{{  __('menu.logout') }}</a>
                </li>
                @if(auth()->user()->checkAdmin())
                    <li class="nav-item">
                        <a class="nav-link" href="/admin">{{  __('menu.admin') }}</a>
                    </li>
                @endif
                @if(auth()->user()->checkManager())
                    <li class="nav-item">
                        <a class="nav-link" href="/manager">{{  __('menu.manager_menu') }}</a>
                    </li>
                @endif

                @if(auth()->user()->checkExpert())
                    <li class="nav-item">
                        <a class="nav-link" href="/expert">{{  __('menu.expert_menu') }}</a>
                    </li>
                @endif
            @endif
        </ul>
    </nav>
</header>
<div class="container-fluid">
    @yield('content')
</div>
@if(session()->has('flash_message'))
    <script>
        Swal.fire({title: 'Message from server', text: "{{ session()->pull('flash_message') }}"});
    </script>
@endif
</body>
</html>
