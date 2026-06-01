<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | TicketFlow</title>
    <link rel="stylesheet" href="{{ asset('css/admin/login.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <Header>
        <img src="{{ asset('assets/logo.webp') }}" alt="Logo" class="logo">
    </Header>
    <Main>
        <section class="main-kiri">
            <div>
                <img src="{{ asset('assets/mainImage.webp') }}" alt="" class="back-image">
            </div>
        </section>

        <section class="main-kanan">
            <div class="form">
                <h3>Selamat Datang</h3>
                <p>Silahkan Login untuk Proses ke Dashboard</p>

                <button class="btn btn-primary">Tes</button>
                <div class="input">
                    <label for="">Username</label>
                    <input type="text" placeholder="Masukkan Username">
                </div>
                <div class="input">
                    <label for="">Password</label>
                    <input type="text" placeholder="Masukkan Password">
                </div>
                <a href="{{ 'dashboard' }}" class="button">Masuk</a>
                <a href="{{ 'dashboard' }}" class="teks">Kembali ke Halaman Utama</a>
            </div>
        </section>
    </Main>
</body>
</html>