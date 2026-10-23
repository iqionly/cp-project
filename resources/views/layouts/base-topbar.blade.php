@extends('base')

@section('layouts')

    <!--
            Top-nav layout: there is NO <aside class="app-sidebar"> on this page, so the
            app-wrapper grid's sidebar column (grid-template-columns: auto 1fr) collapses
            to zero width on its own — header, main, and footer span the full viewport.
            The primary navigation lives in the app-header as a plain Bootstrap
            navbar-expand-lg (Collapse + toggler on mobile). No PushMenu, and therefore
            no sidebar-expand-* class on <body>.
          -->
    <!--begin::Body-->

    <body class="bg-body-tertiary">
        <!--begin::App Wrapper-->
        <div class="app-wrapper">
            @include('layouts.topbar')
            <!--begin::App Main-->
            <main class="app-main">
                <!--begin::App Content Header-->
                <div class="app-content-header">
                    <!--begin::Container-->
                    <div class="container">
                        <!--begin::Row-->
                        <div class="row">
                            @if(!request()->routeIs('public.posts'))
                            <div class="col-sm-6">
                                <h1 class="mb-0 fs-3">{{ isset($page_title) ? $page_title : null }}</h1>
                            </div>
                            @endif
                            <div class="col-sm-{{ request()->routeIs('public.posts') ? 12 : 6 }}">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb float-sm-end">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        @foreach ($page_breadcumb ?? [] as $title)
                                            <li class="breadcrumb-item {{ $loop->last ? 'active' : null }}"
                                                aria-current="page">{{ $title }}</li>
                                        @endforeach
                                    </ol>
                                </nav>
                            </div>
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Container-->
                </div>
                <!--end::App Content Header-->
                <!--begin::App Content-->
                <div class="app-content">
                    <!--begin::Container-->
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-md-12">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @yield('content')
                    </div>
                    <!--end::Container-->
                </div>
                <!--end::App Content-->
            </main>
            <!--end::App Main-->
            @include('layouts.footer')
        </div>
        <!--end::App Wrapper-->
    </body>
    <!--end::Body-->
@endsection
