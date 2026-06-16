<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

<aside id="sidebar">

    @php
        $menus = [
            [
                'name' => 'Dashboard',
                'route' => 'admin.pages.dashboard',
                'icon' => 'bi-grid',
            ],
            [
                'name' => 'Category',
                'route' => 'admin.pages.category.index',
                'icon' => 'bi-grid-3x3-gap',
            ],
            [
                'name' => 'Ticket',
                'route' => 'admin.pages.ticket',
                'icon' => 'bi-ticket-perforated',
            ],
            [
                'name' => 'Documentation',
                'route' => 'admin.pages.documentation',
                'icon' => 'bi-file-earmark-text',
            ],
            [
                'name' => 'IKB',
                'route' => 'admin.pages.ikb',
                'icon' => 'bi-briefcase',
            ],
            [
                'name' => 'Logs',
                'route' => 'admin.pages.logs',
                'icon' => 'bi-list-ul',
            ],
            [
                'name' => 'Report',
                'route' => 'admin.pages.report',
                'icon' => 'bi-bar-chart',
            ],
            [
                'name' => 'User',
                'route' => 'admin.pages.user.index',
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