@extends('layouts.base-sidebar')

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card card-outline card-primary shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title fw-bold">
                        <i class="bi bi-person-plus-fill me-2 text-primary"></i>Create Member Account
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('master-data.member.list') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back to List
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->

                <form action="{{ route('master-data.member.create') }}" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="callout callout-info mb-4">
                            <h5 class="fw-bold"><i class="bi bi-info-circle me-1"></i> First-Time Member Setup</h5>
                            <p class="mb-0 text-body-secondary">
                                Determine the member's username and default password here. The member will log in using these initial credentials. Upon their first login, they will be prompted to complete their profile (email, address, phone number, NIK, etc.) and can change their password and username.
                            </p>
                        </div>

                        <div class="mb-3">
                            <label for="member_code" class="form-label fw-bold">Member Code</label>
                            <input type="text"
                                   class="form-control @error('member_code') is-invalid @enderror"
                                   id="member_code"
                                   name="member_code"
                                   value="{{ old('member_code', $defaultMemberCode) }}"
                                   required />
                            <div class="form-text">System generated unique member identification code.</div>
                            @error('member_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label fw-bold">Member Username <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text"
                                       class="form-control @error('username') is-invalid @enderror"
                                       id="username"
                                       name="username"
                                       placeholder="e.g. johndoe"
                                       value="{{ old('username') }}"
                                       required />
                            </div>
                            <div class="form-text">The username assigned to the member to log in. The member can change this later.</div>
                            @error('username')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">Default Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="text"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       value="{{ old('password', $defaultPassword) }}"
                                       required />
                            </div>
                            <div class="form-text">The default temporary password for the member to use on first login.</div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <a href="{{ route('master-data.member.list') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-1"></i> Create Member Account
                        </button>
                    </div>
                    <!-- /.card-footer -->
                </form>
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection
