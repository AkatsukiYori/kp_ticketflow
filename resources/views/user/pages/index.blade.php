<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TicketFlow</title>
    <link rel="stylesheet" href="{{ asset('css/user/user.css') }}">
</head>
<body>
    <header>
        <div class="header">
            <div class="logo">
                <img src="{{ asset('assets/logo.webp') }}" alt="Logo">
                <div class="judul">
                    <h1>TicketFlow</h1>
                </div>
            </div>
            <button class="btn-cek">
                Beranda 
            </button>
        </div>
    </header>
    <main>
        <section class="main-kiri">
            <div class="gap">
                <h2>
                    Helpdesk Internal
                </h2>
                <p>
                    Digunakan oleh seluruh tim internal untuk memastikan
                    layanan IT berjalan cepat, transparan dan terdokumentasi.
                </p>
                <p>
                    Setiap tiket tercatat, diprioritaskan dan ditangani oleh
                    tim IT terkait
                </p>
                <div class="button">
                    <button class="btn-new">
                        Buat Tiket Baru
                    </button>
                    <button class="btn-cek">
                        Cek Status Tiket
                    </button>
                </div>
            </div>
        </section>
        <section class="main-kanan">
            <img src="{{ asset('assets/mainImage.webp') }}"
            alt = ""
            width = "100%"
            height = "100%"
            >
        </section>
    </main>
</body>
</html>