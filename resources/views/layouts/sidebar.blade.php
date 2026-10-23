<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="/" class="brand-link">
            <!--begin::Brand Image-->
            <img src="" alt="Logo Club" class="brand-image opacity-75 shadow" />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">Mgmt Club</span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->
    <!--begin::Sidebar Search-->
    <div class="sidebar-search" role="search">
        <label for="sidebar-search-input" class="visually-hidden">Filter menu</label>
        <input type="search" id="sidebar-search-input" class="form-control form-control-sm" placeholder="Filter menu…"
            autocomplete="off" data-lte-toggle="sidebar-search" data-lte-target="#navigation" />
        <p class="fs-7 text-secondary mt-2 mb-0" data-lte-search-empty role="status" hidden>
            No matching pages.
        </p>
    </div>
    <!--end::Sidebar Search-->
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2" aria-label="Main navigation">
            <!--begin::Sidebar Menu-->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" data-accordion="false" id="navigation">

                @can('role', ['admin'])
                    <li class="nav-item {{ request()->routeIs('master-data.*') || request()->routeIs('post.*') || request()->routeIs('event.*') ? 'menu-open' : null }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon bi bi-speedometer"></i>
                            <p>
                                Master Data
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('master-data.member.list') }}"
                                    class="nav-link {{ request()->routeIs('master-data.member.*') ? 'active' : null }}">
                                    <i class="nav-icon bi bi-people"></i>
                                    <p>Members</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('post.index') }}" class="nav-link {{ request()->routeIs('post.*') ? 'active' : null }}">
                                    <i class="nav-icon bi bi-stickies-fill"></i>
                                    <p>Posts</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('event.index') }}" class="nav-link  {{ request()->routeIs('event.*') ? 'active' : null }}">
                                    <i class="nav-icon bi bi-calendar-event-fill"></i>
                                    <p>Events</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('master-data.user.list') }}"
                                    class="nav-link {{ request()->routeIs('master-data.user.*') ? 'active' : null }}">
                                    <i class="nav-icon bi bi-people"></i>
                                    <p>Users</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                @endcan
                @can('role', ['admin', 'member'])
                    <li class="nav-item">
                        <a href="{{ route('member.profile') }}" class="nav-link  {{ request()->routeIs('member.profile*') ? 'active' : null }}">
                            <i class="nav-icon bi bi-person-circle"></i>
                            <p>My Profile</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('contrib.index') }}" class="nav-link  {{ request()->routeIs('contrib.*') ? 'active' : null }}">
                            <i class="nav-icon bi bi-cash-coin"></i>
                            <p>Invoices</p>
                        </a>
                    </li>
                @endcan
                @can('role', ['admin'])

                    <li class="nav-item {{ request()->routeIs('invoice.*') || request()->routeIs('menu-setting.*') ? 'menu-open' : null }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon bi bi-gear"></i>
                            <p>
                                Settings
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('invoice.generate') }}" class="nav-link  {{ request()->routeIs('invoice.generate') ? 'active' : null }}">
                                    <i class="nav-icon bi bi-cash-stack"></i>
                                    <p>Generate Invoice</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('menu-setting.index') }}" class="nav-link  {{ request()->routeIs('menu-setting.*') ? 'active' : null }}">
                                    <i class="nav-icon bi bi-menu-app"></i>
                                    <p>Menu Setting</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                @endcan

            </ul>
            <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->
