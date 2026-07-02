<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

<aside id="sidebar">

    @php
        $menus = [
            [
                'name' => 'Dashboard',
                'route' => 'admin.pages.dashboard.index',
                'icon' => 'bi-grid',
                'roles' => ['admin']
            ],
            [
                'name' => 'Tiket',
                'route' => 'admin.pages.ticket.index',
                'icon' => 'bi-ticket-perforated',
                'roles' => ['admin']
            ],
            [
                'name' => 'Ticket IKB',
                'route' => 'admin.pages.ikb.index',
                'icon' => 'bi-briefcase',
                'roles' => ['admin', 'ikb']
            ],
            [
                'name' => 'Kategori',
                'route' => 'admin.pages.category.index',
                'icon' => 'bi-grid-3x3-gap',
                'roles' => ['admin']
            ],
            [
                'name' => 'Pengguna',
                'route' => 'admin.pages.member.index',
                'roles' => ['admin']
            ],
            [
                'name' => 'Dokumentasi',
                'route' => 'admin.pages.documentation.index',
                'icon' => 'bi-file-earmark-text',
                'roles' => ['admin']
            ],
            [
                'name' => 'Laporan & Statistik',
                'route' => 'admin.pages.report.index',
                'icon' => 'bi-bar-chart',
                'roles' => ['admin']
            ],
            [
                'name' => 'Log',
                'route' => 'admin.pages.logs.index',
                'icon' => 'bi-list-ul',
                'roles' => ['admin']
            ]
        ];
    @endphp

    @foreach ($menus as $menu)
        @if(in_array(Auth::user()->role, $menu['roles']))
            @if($menu['name'] !== 'logout')
                <a href="{{ route($menu['route']) }}">
                    <section class="{{ request()->routeIs($menu['route']) ? 'active' : '' }}">
                        <p>{{ $menu['name'] }}</p>
                    </section>
                </a>
            @endif
        @endif
    @endforeach

    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button type="submit" class="logout-btn">
            <section>
                <p>Keluar</p>
            </section>
        </button>
    </form>
</aside>