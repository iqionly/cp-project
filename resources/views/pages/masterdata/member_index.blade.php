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
                                    <td>{{ $member->member_code }}</td>
                                    <td>{{ $member->full_name }}</td>
                                    <td>
                                        @if ($member->user)
                                            <span class="badge text-bg-success">verified</span>
                                        @else
                                            <span class="badge text-bg-warning">need account</span>
                                        @endif
                                    </td>
                                    <td>{{ $member->phone_number }}</td>
                                    <td>{{ $member->user->email }}</td>
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

                    <div class="text-center">
                        <div class="btn-group m-2" role="group" aria-label="pagination">
                            @for ($i = 1; $i <= max(10, $data->lastPage()); $i++)
                                @if ($data->currentPage() > 1 && $i == 1)
                                    <a href="?page={{$data->currentPage() - 1}}" class="btn btn-secondary">Prev</a>
                                @endif
                                <a href="?page={{ $i }}" class="btn btn-secondary {{ $data->currentPage() == $i ? 'active' : null }}">{{ $i }}</a>
                                @if ($data->currentPage() < 10 && $i == 10)
                                    <a href="?page={{$data->currentPage() + 1}}" class="btn btn-secondary">Next</a>
                                @endif
                            @endfor
                        </div>
                    </div>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!--end::Row-->
@endsection
