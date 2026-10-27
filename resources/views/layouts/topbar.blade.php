<!--begin::Header-->
<nav class="app-header navbar navbar-expand-lg bg-body">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Brand-->
        <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center">
            <img src="{{ url('/assets/logo1.jpeg')}}" alt="CLUB MGMT" width="30" height="30" class="opacity-75 shadow me-2" />
            <img src="{{ url('/assets/logo2.jpeg')}}" alt="CLUB MGMT" width="30" height="30" class="opacity-75 shadow me-2" />
            <span class="fw-light">CLUB MGMT</span>
        </a>
        <!--end::Brand-->
        <!--begin::Toggler (plain Bootstrap collapse, not PushMenu)-->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavMenu"
            aria-controls="topNavMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!--end::Toggler-->
        <!--begin::Collapsible Menu-->
        <div class="collapse navbar-collapse" id="topNavMenu">
            <ul class="navbar-nav">
                @foreach($menus ?? [] as $menu)
                <li class="nav-item">
                    <a class="nav-link {{ request()->is($menu->slug) || request()->is($menu->slug.'/*') ? 'active' : '' }}" href="{{ route('public.posts', [$menu->slug])}}">{{ $menu->menu_title }}</a>
                </li>
                @endforeach
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('auth.login') }}">
                        LOGIN
                        <i class="bi bi-box-arrow-in-left ms-1" aria-hidden="true"></i>
                    </a>
                </li>
            </ul>
        </div>
        <!--end::Collapsible Menu-->
    </div>
    <!--end::Container-->
</nav>
<!--end::Header-->
