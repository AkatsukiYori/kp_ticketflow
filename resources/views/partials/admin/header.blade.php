<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">

<header>
        <div>
            <img src="{{ asset('assets/logo2.webp') }}" alt="Logo">
            <div class="nama">
                <h4 class="fw-bold m-0">{{ Auth::user()->username }}</h4>
                <p class="m-0">{{ Auth::user()->location }}</p>
            </div>
        </div>
    </header>