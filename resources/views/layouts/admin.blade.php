<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
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

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    @yield('script')
</body>
</html>