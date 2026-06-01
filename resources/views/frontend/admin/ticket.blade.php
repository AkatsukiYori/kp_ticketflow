<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket | TicketFlow</title>
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
</head>
<body>
    <header>
        <div>
            <img src="{{ asset('assets/logo2.webp') }}" alt="Logo">
            <div class="nama">
                <h2>Asexander</h2>
                <p>Pontianak</p>
            </div>

        </div>
    </header>
    <main>
        <aside>
           <a href="{{ 'dashboard' }}">
            <section>
                <p>
                    Dashboard
                </p>
           </section>
           </a>
            <a href="{{ 'ticket' }}">
            <section class="active">
                <p>
                    Ticket
                </p>
           </section>
            </a>
           <a href="{{ 'ikb' }}">
            <section>
                <p>
                    IKB
                </p>
           </section>
           </a>
           <a href="{{ 'category' }}">
            <section>
                <p>
                    Category
                </p>
           </section>
           </a>
           <a href="{{ 'user' }}">
            <section>
                <p>
                    Users
                </p>
           </section>
           </a>
           <a href="{{ 'documentation' }}">
            <section>
                <p>
                    Documentation
                </p>
           </section>
           </a>
           <a href="{{ 'report' }}">
            <section>
                <p>
                    Report & Statistic
                </p>
           </section>
           </a>
           <a href="{{ 'logs' }}">
            <section>
                <p>
                    Logs
                </p>
           </section>
           </a>
            <a href="{{ 'logout' }}">
                <section>
                <p>
                    Log Out
                </p>
           </section>
            </a>
        </aside>
        <section class="mainpage">
            <p>Halaman Tiket</p>
        </section>
    </main>
    <footer>
        <p>&copy;2026 TicketFlow</p>
    </footer>
</body>
</html>