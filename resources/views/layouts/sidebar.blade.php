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
                        <li class="nav-item">
                            <a href="./index2.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Dashboard v2</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./index3.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Dashboard v3</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="./starter.html" class="nav-link">
                        <i class="nav-icon bi bi-file-earmark"></i>
                        <p>Starter Page</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./generate/theme.html" class="nav-link">
                        <i class="nav-icon bi bi-palette"></i>
                        <p>Theme Generate</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-box-seam-fill"></i>
                        <p>
                            Widgets
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="./widgets/small-box.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Small Box</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./widgets/info-box.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>info Box</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./widgets/cards.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Cards</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./widgets/social.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Social &amp; Post</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-clipboard-fill"></i>
                        <p>
                            Layout Options
                            <span class="nav-badge badge text-bg-secondary me-3">12</span>
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="./layout/unfixed-sidebar.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Default Sidebar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./layout/fixed-sidebar.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Fixed Sidebar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./layout/fixed-header.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Fixed Header</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./layout/fixed-footer.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Fixed Footer</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./layout/fixed-complete.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Fixed Complete</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./layout/layout-custom-area.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Layout <small>+ Custom Area </small></p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./layout/sidebar-mini.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Sidebar Mini</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./layout/collapsed-sidebar.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Sidebar Mini <small>+ Collapsed</small></p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./layout/collapsed-sidebar-without-hover.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Sidebar Mini <small>+ Collapsed + No Hover</small></p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./layout/logo-switch.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Sidebar Mini <small>+ Logo Switch</small></p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./layout/top-nav.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Top Nav <small>+ No Sidebar</small></p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./layout/layout-rtl.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Layout RTL</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-tree-fill"></i>
                        <p>
                            UI Elements
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="./UI/general.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>General</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./UI/icons.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Icons</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./UI/timeline.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Timeline</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./UI/ribbons.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Ribbons</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./UI/colors.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Colors</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-envelope"></i>
                        <p>
                            Mailbox
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="./mailbox/inbox.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Inbox</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./mailbox/read.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Read Message</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./mailbox/compose.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Compose</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-pencil-square"></i>
                        <p>
                            Forms
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="./forms/elements.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Elements</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./forms/layout.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Layout</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./forms/validation.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Validation</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./forms/wizard.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Wizard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./forms/advanced.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Advanced Elements</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./forms/editors.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Editors</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-table"></i>
                        <p>
                            Tables
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="./tables/simple.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Simple Tables</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./tables/data.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Data Tables</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-graph-up"></i>
                        <p>
                            Charts
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="./charts/apexcharts.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>ApexCharts</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-header">PAGES</li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-file-earmark-text"></i>
                        <p>
                            Pages
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="./pages/profile.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Profile</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./pages/settings.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Settings</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./pages/invoice.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Invoice</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./pages/calendar.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Calendar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./pages/kanban.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Kanban</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./pages/chat.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Chat</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./pages/file-manager.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>File Manager</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./pages/projects.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Projects</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./pages/gallery.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Gallery</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./pages/search-results.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Search Results</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./pages/pricing.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Pricing</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./pages/faq.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>FAQ</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>
                                    Error
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="./pages/404.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>404</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./pages/500.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>500</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./pages/maintenance.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Maintenance</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="./users.html" class="nav-link">
                        <i class="nav-icon bi bi-people"></i>
                        <p>Users</p>
                    </a>
                </li>


            </ul>
            <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->
