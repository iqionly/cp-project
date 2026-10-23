@extends('layouts.base-sidebar')

@section('content')
    <!--begin::Row-->
    <div class="row">

        <div class="col-md-12">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title fw-bold">List Member</h3>
                    <div class="card-tools">
                        <a href="{{ route('master-data.member.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-person-plus-fill me-1"></i> CREATE MEMBER
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Member Code</th>
                                <th>Member Name</th>
                                <th>Account Status</th>
                                <th>Phone Number</th>
                                <th>Email Address</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $member)
                                <tr class="align-middle">
                                    <td>{{ $loop->iteration }}</td>
                                    <td><a href="#">{{ $member->member_code }}</a></td>
                                    <td>{{ $member->full_name }}</td>
                                    <td>
                                        @if ($member->user && $member->user->isProfileComplete())
                                            <span class="badge text-bg-success">Complete</span>
                                        @elseif ($member->user)
                                            <span class="badge text-bg-warning">Pending Details</span>
                                        @else
                                            <span class="badge text-bg-danger">Need Account</span>
                                        @endif
                                    </td>
                                    <td>{{ $member->masked_phone_number }}</td>
                                    <td>
                                        @if ($member->user && $member->user->email)
                                            {{ $member->user->masked_email }}
                                        @else
                                            <span class="text-body-secondary fst-italic">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ $member->created_at }}</td>
                                    <td>
                                        <form action="{{ route('master-data.member.delete', [$member->id]) }}" method="post">
                                            @method('DELETE')
                                            @csrf
                                            <input type="submit" class="btn btn-danger btn-sm m-1" value="DELETE" />
                                        </form>
                                        <a href="{{ route('master-data.member.edit', [$member->id]) }}" class="btn btn-info btn-sm m-1">EDIT</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $data->links() }}

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!--end::Row-->
@endsection
