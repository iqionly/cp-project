@extends('layouts.base-sidebar')

@section('content')
    <!--begin::Row-->
    <div class="row">

        <div class="col-md-12">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Edit User</h3>
                </div>
                <!-- /.card-header -->
                <form method="post">
                    @method('put')
                    @csrf
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" aria-describedby="name"
                                    name="name" value="{{ old('name') ?? $data->name }}" />
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" id="email" aria-describedby="email"
                                    name="email" value="{{ old('email') ?? $data->email }}" />
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="roles" class="form-label">Roles</label>
                                <select name="roles[]" id="roles" class="form-select" multiple size="3">
                                    @foreach($roles ?? [] as $role)
                                        <option value="{{ $role->id }}"
                                            {{ in_array($role->id, old('roles', $userRoleIds ?? ($data->roles ? $data->roles->pluck('id')->toArray() : []))) ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}{{ $role->role_grouping ? ' ('.$role->role_grouping.')' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">Pilih role untuk user (bisa memilih lebih dari satu).</div>
                            </div>

                            <div class="col-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" name="verified"
                                        id="verified" {{ ($data->is_verified ? 'checked': null) ?? null }}>
                                    <label class="form-check-label" for="verified">
                                        Verified?
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="opassword" class="form-label">Old Password</label>
                                <input type="password" class="form-control" id="opassword" aria-describedby="password" name="old_password" />
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="npassword" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="npassword" aria-describedby="password" name="password" />
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="rpassword" class="form-label">Repeat New Password</label>
                                <input type="password" class="form-control" id="rpassword" aria-describedby="password" name="password_confirmation" />
                            </div>

                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!--end::Row-->
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof TomSelect !== 'undefined' && document.getElementById('roles')) {
            new TomSelect('#roles', {
                plugins: ['remove_button'],
                placeholder: 'Pilih role...',
                maxItems: null,
            });
        }
    });
</script>
@endpush
