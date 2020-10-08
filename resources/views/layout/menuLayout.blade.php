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
    <script src="/js/roleName.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

@yield('content')

@if(session()->has('flash_message'))
    <script>
        Swal.fire({title: 'Message from server', text: "{{ session()->pull('flash_message') }}"});
    </script>
@endif
</body>
</html>
