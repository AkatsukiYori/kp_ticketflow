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
                'icon' => 'bi-people',
            ],
            [
                'name' => 'Logout',
                'route' => 'admin.pages.index',
                'icon' => 'bi-box-arrow-right',
            ],
        ];
    @endphp

    @foreach ($menus as $menu)
        <a href="{{ route($menu['route']) }}">
            <section class="{{ request()->routeIs($menu['route']) ? 'active' : '' }}">
                <i class="bi {{ $menu['icon'] }}"></i>
                <p>{{ $menu['name'] }}</p>
            </section>
        </a>
    @endforeach

</aside>