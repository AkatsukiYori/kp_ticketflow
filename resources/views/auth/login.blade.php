<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | TicketFlow</title>

    <link rel="stylesheet" href="{{ asset('css/admin/login2.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<header>
    <img src="{{ asset('assets/logo.webp') }}" alt="Logo" class="logo">
</header>

<main>
    <section class="main-kiri">
        <img src="{{ asset('assets/mainImage.webp') }}" class="back-image">
    </section>

    <section class="main-kanan">

        <form class="form" method="POST" action="{{ route('login.store') }}">
            @csrf

            <h3>Selamat Datang</h3>
            <p>Silahkan Login untuk Proses ke Dashboard</p>

            <div class="input">
                <label>Username</label>
                <input type="text" name="username" placeholder="Masukkan Username">
            </div>

            <div class="input">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan Password">
            </div>

            <button type="button" class="btn btn-primary">
                Masuk
            </button>

            <a href="{{ route('admin.pages.dashboard') }}" class="teks">
                Kembali ke Dashboard
            </a>

        </form>

    </section>

</main>

</body>
</html>
