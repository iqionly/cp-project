<!--begin::Header-->
<nav class="app-header navbar navbar-expand bg-body">
    <!--begin::Container-->
    <div class="container-fluid">

        <!--begin::Navbar Search-->
        <form class="navbar-search d-none d-md-block ms-3" role="search" action="./pages/search-results.html">
            <label for="navbar-search-input" class="visually-hidden">Search</label>
            <div class="navbar-search-field">
                <input type="search" id="navbar-search-input" name="q" class="form-control" placeholder="Search…"
                    autocomplete="off" />
                <button class="navbar-search-submit" type="submit" aria-label="Submit search">
                    <i class="bi bi-search" aria-hidden="true"></i>
                </button>
            </div>
        </form>
        <!--end::Navbar Search-->

        <!--begin::End Navbar Links-->
        <ul class="navbar-nav ms-auto">

            <!--begin::Notifications Dropdown Menu-->
            <li class="nav-item dropdown">
                <a class="nav-link" data-bs-toggle="dropdown" href="#" aria-label="Notifications: 15 unread">
                    <i class="bi bi-bell-fill"></i>
                    <span class="navbar-badge badge text-bg-warning">15</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <span class="dropdown-item dropdown-header">15 Notifications</span>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="bi bi-envelope me-2"></i> 4 new messages
                        <span class="float-end text-secondary fs-7">3 mins</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="bi bi-people-fill me-2"></i> 8 friend requests
                        <span class="float-end text-secondary fs-7">12 hours</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="bi bi-file-earmark-fill me-2"></i> 3 new reports
                        <span class="float-end text-secondary fs-7">2 days</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer"> See All Notifications </a>
                </div>
            </li>
            <!--end::Notifications Dropdown Menu-->

            <!--begin::Color Mode Toggle (#6010)-->
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" id="bd-theme" aria-label="Toggle color scheme"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-sun-fill" data-lte-theme-icon="light"></i>
                    <i class="bi bi-moon-fill d-none" data-lte-theme-icon="dark"></i>
                    <i class="bi bi-circle-half d-none" data-lte-theme-icon="auto"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bd-theme"
                    style="--bs-dropdown-min-width: 8rem">
                    <li>
                        <button type="button" class="dropdown-item d-flex align-items-center"
                            data-bs-theme-value="light" aria-pressed="false">
                            <i class="bi bi-sun-fill me-2"></i>
                            Light
                            <i class="bi bi-check-lg ms-auto d-none"></i>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item d-flex align-items-center"
                            data-bs-theme-value="dark" aria-pressed="false">
                            <i class="bi bi-moon-fill me-2"></i>
                            Dark
                            <i class="bi bi-check-lg ms-auto d-none"></i>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item d-flex align-items-center active"
                            data-bs-theme-value="auto" aria-pressed="true">
                            <i class="bi bi-circle-half me-2"></i>
                            Auto
                            <i class="bi bi-check-lg ms-auto d-none"></i>
                        </button>
                    </li>
                </ul>
            </li>
            <!--end::Color Mode Toggle-->

            @php
                $user_logged_name = request()->user() && request()->user()->member ? request()->user()->member->full_name : request()->user()->name;
            @endphp

            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <img src="./assets/img/user2-160x160.jpg" class="user-image rounded-circle shadow"
                        alt="{{ substr($user_logged_name, 0, 1) }}" />
                    <span class="d-none d-md-inline">{{ substr($user_logged_name, 0, 1) }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <!--begin::User Image-->
                    <li class="user-header text-bg-primary">
                        <img src="./assets/img/user2-160x160.jpg" class="rounded-circle shadow"
                            alt="{{ $user_logged_name }}" />
                        <p>
                            {{ $user_logged_name }}
                        </p>
                    </li>
                    <!--end::User Image-->
                    <!--begin::Menu Footer-->
                    <li class="user-footer">
                        <a href="#" class="btn btn-outline-secondary">Profile</a>
                        <a href="{{ route('auth.logout') }}" class="btn btn-outline-danger float-end">Sign out</a>
                    </li>
                    <!--end::Menu Footer-->
                </ul>
            </li>
            <!--end::User Menu Dropdown-->
        </ul>
        <!--end::End Navbar Links-->
    </div>
    <!--end::Container-->
</nav>
<!--end::Header-->
