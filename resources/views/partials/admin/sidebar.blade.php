<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">

<aside id="sidebar">

    @php
        $menus = [
            [
                'name' => 'Dashboard',
                'route' => 'admin.pages.dashboard',
            ],
            [
                'name' => 'Category',
                'route' => 'admin.pages.category',
            ],
            [
                'name' => 'Ticket',
                'route' => 'admin.pages.ticket',
            ],
            [
                'name' => 'Documentation',
                'route' => 'admin.pages.documentation',
            ],
            [
                'name' => 'IKB',
                'route' => 'admin.pages.ikb',
            ],
            [
                'name' => 'Logs',
                'route' => 'admin.pages.logs',
            ],
            [
                'name' => 'Report',
                'route' => 'admin.pages.report',
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