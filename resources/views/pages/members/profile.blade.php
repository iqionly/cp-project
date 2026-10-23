@extends('layouts.base-sidebar')

@section('content')
    <div class="row">
        <div class="col-lg-10 offset-lg-1">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('warning'))
                <div class="callout callout-warning mb-4 shadow-sm">
                    <h5 class="fw-bold"><i class="bi bi-exclamation-triangle-fill me-2 text-warning"></i>Action Required: Complete Your Details</h5>
                    <p class="mb-0">
                        {{ session('warning') }}
                    </p>
                </div>
            @elseif (! $isComplete)
                <div class="callout callout-warning mb-4 shadow-sm">
                    <h5 class="fw-bold"><i class="bi bi-exclamation-triangle-fill me-2 text-warning"></i>First-Time Member Setup</h5>
                    <p class="mb-0">
                        Your account was created by an administrator. Please fill in your details (email, address, contact, and identity info) below. You can also update your username and choose your own password.
                    </p>
                </div>
            @endif

            <form action="{{ route('member.profile') }}" method="post">
                @csrf
                @method('put')

                <!--begin::Account Credentials Card-->
                <div class="card card-outline card-primary shadow-sm mb-4">
                    <div class="card-header">
                        <h3 class="card-title fw-bold">
                            <i class="bi bi-person-lock me-2 text-primary"></i>Account Information (Users)
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-bold">Username <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $user->name) }}"
                                           required />
                                </div>
                                <div class="form-text">You can change your login username here.</div>
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           placeholder="e.g. yourname@example.com"
                                           value="{{ old('email', $user->email) }}"
                                           required />
                                </div>
                                <div class="form-text">Your primary contact and notification email.</div>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label fw-bold">New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-key"></i></span>
                                    <input type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           id="password"
                                           name="password"
                                           placeholder="Leave blank to keep current password" />
                                </div>
                                <div class="form-text">Enter a new password if you want to change from the default.</div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-bold">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-check-lg"></i></span>
                                    <input type="password"
                                           class="form-control"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           placeholder="Repeat new password" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Account Credentials Card-->

                <!--begin::Member Personal Information Card-->
                <div class="card card-outline card-info shadow-sm mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title fw-bold">
                            <i class="bi bi-person-vcard me-2 text-info"></i>Personal Information (Member Details)
                        </h3>
                        @if ($member && $member->member_code)
                            <div class="card-tools">
                                <span class="badge text-bg-secondary fs-6">Member Code: {{ $member->member_code }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label fw-bold">First Name <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('first_name') is-invalid @enderror"
                                       id="first_name"
                                       name="first_name"
                                       value="{{ old('first_name', $member?->first_name) }}"
                                       required />
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="last_name" class="form-label fw-bold">Last Name <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('last_name') is-invalid @enderror"
                                       id="last_name"
                                       name="last_name"
                                       value="{{ old('last_name', $member?->last_name) }}"
                                       required />
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="gender" class="form-label fw-bold">Gender <span class="text-danger">*</span></label>
                                <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="M" {{ old('gender', $member?->gender) === 'M' ? 'selected' : '' }}>Male</option>
                                    <option value="F" {{ old('gender', $member?->gender) === 'F' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="birth_date" class="form-label fw-bold">Birth Date <span class="text-danger">*</span></label>
                                <input type="date"
                                       class="form-control @error('birth_date') is-invalid @enderror"
                                       id="birth_date"
                                       name="birth_date"
                                       value="{{ old('birth_date', $member?->birth_date) }}"
                                       required />
                                @error('birth_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="nik" class="form-label fw-bold">National ID (NIK) <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('nik') is-invalid @enderror"
                                       id="nik"
                                       name="nik"
                                       placeholder="16-digit NIK"
                                       maxlength="16"
                                       value="{{ old('nik', $member?->nik) }}"
                                       required />
                                @error('nik')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Member Personal Information Card-->

                <!--begin::Address & Contact Card-->
                <div class="card card-outline card-success shadow-sm mb-4">
                    <div class="card-header">
                        <h3 class="card-title fw-bold">
                            <i class="bi bi-geo-alt-fill me-2 text-success"></i>Address & Contact Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="phone_number" class="form-label fw-bold">Phone / WhatsApp Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                    <input type="tel"
                                           class="form-control @error('phone_number') is-invalid @enderror"
                                           id="phone_number"
                                           name="phone_number"
                                           placeholder="e.g. 08123456789"
                                           value="{{ old('phone_number', $member?->phone_number) }}"
                                           required />
                                </div>
                                @error('phone_number')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="plate_number" class="form-label fw-bold">Vehicle Plate Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-car-front"></i></span>
                                    <input type="text"
                                           class="form-control @error('plate_number') is-invalid @enderror"
                                           id="plate_number"
                                           name="plate_number"
                                           placeholder="e.g. D 1234 ABC"
                                           value="{{ old('plate_number', $member?->plate_number) }}"
                                           required />
                                </div>
                                @error('plate_number')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="address" class="form-label fw-bold">Residential Address <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('address') is-invalid @enderror"
                                          id="address"
                                          name="address"
                                          rows="3"
                                          placeholder="Enter your complete home address"
                                          required>{{ old('address', $member?->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="emergency_person" class="form-label fw-bold">Emergency Contact Person</label>
                                <input type="text"
                                       class="form-control @error('emergency_person') is-invalid @enderror"
                                       id="emergency_person"
                                       name="emergency_person"
                                       placeholder="Name of relative or friend"
                                       value="{{ old('emergency_person', $member?->emergency_person) }}" />
                                @error('emergency_person')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="emergency_phone_number" class="form-label fw-bold">Emergency Contact Phone</label>
                                <input type="tel"
                                       class="form-control @error('emergency_phone_number') is-invalid @enderror"
                                       id="emergency_phone_number"
                                       name="emergency_phone_number"
                                       placeholder="e.g. 08198765432"
                                       value="{{ old('emergency_phone_number', $member?->emergency_phone_number) }}" />
                                @error('emergency_phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-bold">
                            <i class="bi bi-save me-1"></i> Save Profile Details
                        </button>
                    </div>
                </div>
                <!--end::Address & Contact Card-->
            </form>
        </div>
    </div>
@endsection
