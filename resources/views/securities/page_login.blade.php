@extends('base')

@push('styles')
@endpush


@section('layouts')
    <!--begin::Body-->

    <body class="login-page bg-body-secondary">
        <main class="login-box">
            <h1 class="login-logo">
                <a href="../index2.html"><b>Admin</b>LTE</a>
            </h1>
            <!-- /.login-logo -->
            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Sign in to start your session</p>

                    <form action="{{ route('auth.login') }}" method="post">
                        <label class="visually-hidden" for="loginEmail">Email or Member ID</label>
                        <div class="input-group mb-3">
                            <input id="loginEmail" name="user" type="text" class="form-control" placeholder="Email or Member ID" />
                            <div class="input-group-text">
                                <span class="bi bi-envelope"></span>
                            </div>
                        </div>
                        <label class="visually-hidden" for="loginPassword">Password</label>
                        <div class="input-group mb-3">
                            <input id="loginPassword" name="pin" type="password" class="form-control" placeholder="Password" />
                            <div class="input-group-text">
                                <span class="bi bi-lock-fill"></span>
                            </div>
                        </div>
                        <!--begin::Row-->
                        <div class="row">
                            <div class="col-8">
                                <div class="form-check">
                                    <input class="form-check-input" name="remember" type="checkbox" value="" id="flexCheckDefault" />
                                    <label class="form-check-label" for="flexCheckDefault"> Remember Me </label>
                                </div>
                            </div>
                            <!-- /.col -->
                            <div class="col-4">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">Sign In</button>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!--end::Row-->
                    </form>

                    <p class="mb-1">
                        <a href="forgot-password.html">I forgot my password</a>
                    </p>
                    <p class="mb-0">
                        <a href="register.html" class="text-center"> Register a new membership </a>
                    </p>
                </div>
                <!-- /.login-card-body -->
            </div>
        </main>
        <!-- /.login-box -->

    </body>
    <!--end::Body-->
@endsection
