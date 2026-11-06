<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark" data-enable-persistence="true">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="/" class="brand-link">
            <!--begin::Brand Image-->
            <img src="{{ url('/assets/logo1.jpeg')}}" alt="CLUB MGMT" width="30" height="30" class="brand-image opacity-75 shadow me-2" />
            <img src="{{ url('/assets/logo2.jpeg')}}" alt="CLUB MGMT" width="30" height="30" class="brand-image opacity-75 shadow me-2" />
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
        <label for="sidebar-search-input" class="visually-hidden">Cari menu</label>
        <input type="search" id="sidebar-search-input" class="form-control form-control-sm" placeholder="Cari menu…"
            autocomplete="off" data-lte-toggle="sidebar-search" data-lte-target="#navigation" />
        <p class="fs-7 text-secondary mt-2 mb-0" data-lte-search-empty role="status" hidden>
            Tidak ada menu yang sesuai.
        </p>
    </div>
    <!--end::Sidebar Search-->
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2" aria-label="Main navigation">
            <!--begin::Sidebar Menu-->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" data-accordion="false" id="navigation">

                @can('role', ['admin', 'member'])
                <li class="nav-item {{ request()->routeis('home*') ? 'menu-open' : null }}">
                    <a href="{{ route('home') }}" class="nav-link  {{ request()->routeis('home*') ? 'active' : null }}">
                        <i class="nav-icon bi bi-person-circle"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @endcan

                @can('role', ['admin'])
                    <li class="nav-item {{ request()->routeIs('master-data.*') || request()->routeIs('post.*') || request()->routeIs('event.*') || request()->routeIs('merchandise.*') || request()->routeIs('gallery.*') ? 'menu-open' : null }}">

                        <a href="#" class="nav-link">
                            <i class="nav-icon bi bi-speedometer"></i>
                            <p>
                                Data Master
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('master-data.member.list') }}"
                                    class="nav-link {{ request()->routeIs('master-data.member.*') ? 'active' : null }}">
                                    <i class="nav-icon bi bi-people"></i>
                                    <p>Data Anggota</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('post.index') }}" class="nav-link {{ request()->routeIs('post.*') ? 'active' : null }}">
                                    <i class="nav-icon bi bi-stickies-fill"></i>
                                    <p>Postingan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('event.index') }}" class="nav-link  {{ request()->routeIs('event.*') ? 'active' : null }}">
                                    <i class="nav-icon bi bi-calendar-event-fill"></i>
                                    <p>Kegiatan & Acara</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('merchandise.index') }}" class="nav-link {{ request()->routeIs('merchandise.*') ? 'active' : null }}">
                                    <i class="nav-icon bi bi-bag-check-fill"></i>
                                    <p>Merchandise</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('gallery.index') }}" class="nav-link {{ request()->routeIs('gallery.*') ? 'active' : null }}">
                                    <i class="nav-icon bi bi-images"></i>
                                    <p>Dokumentasi</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('master-data.user.list') }}"
                                    class="nav-link {{ request()->routeIs('master-data.user.*') ? 'active' : null }}">
                                    <i class="nav-icon bi bi-people"></i>
                                    <p>Data Pengguna</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                @endcan
                @can('role', ['admin', 'member'])
                    <li class="nav-item">
                        <a href="{{ route('member.profile') }}" class="nav-link  {{ request()->routeIs('member.profile*') ? 'active' : null }}">
                            <i class="nav-icon bi bi-person-circle"></i>
                            <p>Profil Saya</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('member.agenda') }}" class="nav-link  {{ request()->routeIs('member.agenda') ? 'active' : null }}">
                            <i class="nav-icon bi bi-calendar2-week"></i>
                            <p>Agenda Kegiatan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('member.agenda.history') }}" class="nav-link  {{ request()->routeIs('member.agenda.history*') ? 'active' : null }}">
                            <i class="nav-icon bi bi-clock-history"></i>
                            <p>Riwayat Agenda</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('contrib.index') }}" class="nav-link  {{ request()->routeIs('contrib.*') ? 'active' : null }}">
                            <i class="nav-icon bi bi-cash-coin"></i>
                            <p>Tagihan & Iuran</p>
                        </a>
                    </li>
                @endcan
                @can('role', ['admin'])

                    <li class="nav-item {{ request()->routeIs('invoice.*') || request()->routeIs('menu-setting.*') ? 'menu-open' : null }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon bi bi-gear"></i>
                            <p>
                                Pengaturan
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('invoice.generate') }}" class="nav-link  {{ request()->routeIs('invoice.generate') ? 'active' : null }}">
                                    <i class="nav-icon bi bi-cash-stack"></i>
                                    <p>Buat Tagihan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('menu-setting.index') }}" class="nav-link  {{ request()->routeIs('menu-setting.*') ? 'active' : null }}">
                                    <i class="nav-icon bi bi-menu-app"></i>
                                    <p>Pengaturan Menu</p>
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
