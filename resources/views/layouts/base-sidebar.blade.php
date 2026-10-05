@extends('base')

@section('layouts')
    <!--begin::Body-->
    <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
        <!--begin::App Wrapper-->
        <div class="app-wrapper">
        @include('layouts.header')
        @include('layouts.sidebar')

        <!--begin::App Main-->
        <main class="app-main">
            <!--begin::App Content Header-->
            <div class="app-content-header">
                <!--begin::Container-->
                <div class="container-fluid">
                    <!--begin::Row-->
                    <div class="row">
                        <div class="col-sm-6">
                            <h1 class="mb-0 fs-3">{{ isset($page_title) ? $page_title : null }}</h1>
                        </div>
                        <div class="col-sm-6">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb float-sm-end">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    @foreach($page_breadcumb as $title)
                                    <li class="breadcrumb-item {{ $loop->last ? 'active' : null }}" aria-current="page">{{ $title }}</li>
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
                @yield('content')
                </div>
            </div>
        </main>

        @include('layouts.footer')
        </div>
        <!--end::App Wrapper-->
    </body>
    <!--end::Body-->
@endsection
