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
        <input type="search" id="sidebar-search-input" class="form-control form-control-sm" placeholder="Filter menu…" autocomplete="off" data-lte-toggle="sidebar-search" data-lte-target="#navigation" />
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
                <li class="nav-item {{ request()->routeIs('master-data.*') ? 'menu-open' : null }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>
                            Master Data
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('master-data.member.list') }}" class="nav-link {{ request()->routeIs('master-data.member.list') ? 'active' : null }}">
                                <i class="nav-icon bi bi-people"></i>
                                <p>Members</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('post.index') }}" class="nav-link">
                        <i class="nav-icon bi bi-file-earmark"></i>
                        <p>Posts</p>
                    </a>
                </li>

            </ul>
            <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->
