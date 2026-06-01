<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">

<aside>

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
                'route' => 'admin.pages.user',
            ],
            [
                'name' => 'Logout',
                'route' => 'admin.pages.logout',
            ],
        ];
    @endphp

    @foreach ($menus as $menu)
        <a href="{{ route($menu['route']) }}">
            <section class="{{ request()->routeIs($menu['route']) ? 'active' : '' }}">
                <p>{{ $menu['name'] }}</p>
            </section>
        </a>
    @endforeach

</aside>