@extends('layouts.base-sidebar')

@section('content')
    <!--begin::Row-->
    <div class="row">

        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">List Your Contribution</h3>
                    <div class="card-tools">
                        <form method="post" class="row row-cols-lg-auto g-3 align-items-center">
                            <div class="col-3">
                                <div class="input-group">
                                  <input type="text" class="form-control" name="filter_member_first_name" placeholder="Search member" aria-label="Search member" value="{{ request()->filter_member_first_name }}">
                                  <button class="btn btn-outline-secondary" type="submit">SEARCH</button>
                                </div>
                            </div>
                            <div class="col-3"><a href="{{ route('contrib.create') }}" class="btn btn-primary btn-sm">UPLOAD PAYMENTS</a></div>
                        </form>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Invoice Number</th>
                                <th>Notes</th>
                                <th>Status</th>
                                <th>Member</th>
                                <th>Period Start At</th>
                                <th>Due At</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $pay)
                                <tr class="align-middle">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $pay->invoice_number }}
                                        <span class="badge text-bg-info">Rp.{{ $pay->total_amount }},-</span>
                                    </td>
                                    <td title="{{ $pay->notes }}">{{ $pay->notes }}...</td>
                                    <td>
                                        @if ($pay->payed)
                                            <span class="badge text-bg-success">PAYED</span>
                                        @elseif($pay->not_yet)
                                            <span class="badge text-bg-secondary">NOT YET</span>
                                        @elseif($pay->partially)
                                            <span class="badge text-bg-warning">PARTIAL</span>

                                        @endif
                                    </td>
                                    <td>{{ $pay->member->full_name }}</td>
                                    <td>{{ $pay->period_start }}</td>
                                    <td>{{ $pay->due_at }}</td>
                                    <td>{{ $pay->updated_at }}</td>
                                    <td>
                                        <form action="{{ route('contrib.delete', [$pay->id]) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <input type="submit" class="btn btn-danger btn-sm m-1" value="DELETE" />
                                        </form>
                                        <a href="{{ route('contrib.edit', [$pay->id]) }}"
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

        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Payment History</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Payment Number</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Paid At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($payments as $payment)
                                <tr class="align-middle">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $payment->payment_number }}</td>
                                    <td>Rp.{{ number_format($payment->amount, 0, ',', '.') }},-</td>
                                    <td>{{ strtoupper($payment->method) }}</td>
                                    <td>
                                        @if ($payment->status == 'APPROVED')
                                            <span class="badge text-bg-success">APPROVED</span>
                                        @elseif($payment->status == 'REVIEWED')
                                            <span class="badge text-bg-info">REVIEWED</span>
                                        @elseif($payment->status == 'REJECTED')
                                            <span class="badge text-bg-danger">REJECTED</span>
                                        @else
                                            <span class="badge text-bg-secondary">PENDING</span>
                                        @endif
                                    </td>
                                    <td>{{ $payment->paid_at }}</td>
                                    <td>
                                        @if ($payment->evidence_path)
                                            <a href="{{ asset('storage/' . $payment->evidence_path) }}" target="_blank"
                                                class="btn btn-sm btn-info m-1">VIEW EVIDENCE</a>
                                        @endif
                                        @can('role', ['admin'])
                                            @switch($payment->status)
                                                @case('REVIEWED')
                                                <form action="{{ route('contrib.admin.review', [$payment->id, true]) }}"
                                                    method="post">
                                                    @csrf
                                                    <input type="submit" value="APPROVE" class="btn btn-sm btn-success m-1" />
                                                </form>
                                                <form action="{{ route('contrib.admin.review', [$payment->id, false]) }}"
                                                    method="post">
                                                    @csrf
                                                    <input type="submit" value="REJECT" class="btn btn-sm btn-danger m-1" />
                                                </form>
                                                @break
                                                @case('REJECTED')
                                                @case(null)
                                                <form action="{{ route('contrib.admin.review', [$payment->id, true]) }}"
                                                    method="post">
                                                    @csrf
                                                    <input type="submit" value="VERIFY" class="btn btn-sm btn-success m-1" />
                                                </form>
                                                <form action="{{ route('contrib.admin.review', [$payment->id, false]) }}"
                                                    method="post">
                                                    @csrf
                                                    <input type="submit" value="REJECT" class="btn btn-sm btn-danger m-1" />
                                                </form>
                                                @break
                                                @case('APPROVED')
                                                <form action="{{ route('contrib.admin.review', [$payment->id, false]) }}"
                                                    method="post">
                                                    @csrf
                                                    <input type="submit" value="REJECT" class="btn btn-sm btn-danger m-1" />
                                                </form>
                                                @break
                                            @endswitch
                                        @endcan
                                            @if(empty($payment->status) || Auth::user()->can('role', ['admin']) )
                                            <form action="{{ route('contrib.payments.destroy', [$payment->id]) }}"
                                                method="post">
                                                @method('DELETE')
                                                @csrf
                                                <input type="submit" value="DELETE" class="btn btn-sm btn-danger m-1" />
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="99">No payments mades.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $payments->links() }}
                </div>
            </div>
        </div>

    </div>
    <!--end::Row-->
@endsection
