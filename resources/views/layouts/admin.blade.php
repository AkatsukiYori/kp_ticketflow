<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @include('partials.admin.header')

    <main>
        @include('partials.admin.sidebar')

        <section class="mainpage">
            @yield('content')
        </section>
    </main>
    
    @include('partials.admin.footer')
</body>
</html>