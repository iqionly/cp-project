@extends('layouts.base-sidebar')

@section('content')
    <!--begin::Row-->
    <div class="row">

        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">List Member</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Verified</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $user)
                                <tr class="align-middle">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->masked_email }}</td>
                                    <td>
                                        @if ($user->email_verified_at)
                                            <span class="badge text-bg-success">verified</span>
                                        @else
                                            <span class="badge text-bg-warning">not verified</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at }}</td>
                                    <td>{{ $user->updated_at }}</td>
                                    <td>
                                        <form action="{{ route('master-data.user.delete', [$user->id]) }}" method="post">
                                            @method('DELETE')
                                            @csrf
                                            <input type="submit" class="btn btn-danger btn-sm m-1" value="DELETE" />
                                        </form>
                                        <a href="{{ route('master-data.user.edit', [$user->id]) }}"
                                            class="btn btn-info btn-sm m-1">EDIT</a>
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
