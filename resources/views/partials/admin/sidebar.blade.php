<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

<aside id="sidebar">

    @php
        $menus = [
            [
                'name' => 'Dashboard',
                'route' => 'admin.pages.dashboard.index',
                'icon' => 'bi-grid',
            ],
            [
                'name' => 'Tiket',
                'route' => 'admin.pages.ticket.index',
                'icon' => 'bi-ticket-perforated',
            ],
            [
                'name' => 'Ticket IKB',
                'route' => 'admin.pages.ikb.index',
                'icon' => 'bi-briefcase',
            ],
            [
                'name' => 'Kategori',
                'route' => 'admin.pages.category.index',
                'icon' => 'bi-grid-3x3-gap',
            ],
            [
                'name' => 'Pengguna',
                'route' => 'admin.pages.member.index',
            ],
            [
                'name' => 'Dokumentasi',
                'route' => 'admin.pages.documentation.index',
                'icon' => 'bi-file-earmark-text',
            ],
            [
                'name' => 'Laporan & Statistik',
                'route' => 'admin.pages.report',
                'icon' => 'bi-bar-chart',
            ],
            [
                'name' => 'Log',
                'route' => 'admin.pages.logs.index',
                'icon' => 'bi-list-ul',
            ]
        ];
    @endphp

    @foreach ($menus as $menu)
        @if($menu['name'] !== 'logout')
            <a href="{{ route($menu['route']) }}">
                <section class="{{ request()->routeIs($menu['route']) ? 'active' : '' }}">
                    <p>{{ $menu['name'] }}</p>
                </section>
            </a>
        @endif
    @endforeach

    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button type="submit" class="logout-btn">
            <section>
                <p>Logout</p>
            </section>
        </button>
    </form>
</aside>