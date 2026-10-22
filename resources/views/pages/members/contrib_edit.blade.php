@extends('layouts.base-sidebar')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Upload Payment Evidence</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('contrib.create') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="invoice_id">Select Invoice</label>
                            <select name="invoice_id" id="invoice_id"
                                class="form-control @error('invoice_id') is-invalid @enderror"
                                {{ isset($selectedInvoice) ? 'disabled' : null }}>
                                <option value=""></option>
                                @foreach ($invoices as $invoice)
                                    <option value="{{ $invoice->id }}"
                                        {{ old('invoice_id') == $invoice->id || (isset($selectedInvoice) && $selectedInvoice->id == $invoice->id) ? 'selected' : '' }}>
                                        {{ $invoice->invoice_number }} -
                                        Rp.{{ number_format($invoice->total_amount, 0, ',', '.') }},-
                                    </option>
                                @endforeach
                            </select>
                            @if (isset($selectedInvoice))
                                <input name="invoice_id" type="hidden" value="{{ $selectedInvoice->id }}" />
                            @endif
                            @error('invoice_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="amount">Payment Amount</label>
                            <input type="text" name="amount" id="amount"
                                class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}"
                                placeholder="e.g. 50000">
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="method">Payment Method</label>
                            <select name="method" id="method"
                                class="form-control @error('method') is-invalid @enderror">
                                <option value="transfer" {{ old('method') == 'transfer' ? 'selected' : '' }}>Bank Transfer
                                </option>
                                <option value="cash" {{ old('method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="other" {{ old('method') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="evidence">Payment Evidence (Image/PDF)</label>
                            <input type="file" name="evidence" id="evidence"
                                class="form-control @error('evidence') is-invalid @enderror">
                            <small class="text-muted">Max size 2MB. Allowed formats: jpg, jpeg, png, pdf.</small>
                            @error('evidence')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <a href="{{ route('contrib.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit Payment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Your Payment History</h3>
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
                            @forelse ($payments as $allocation)
                                <tr class="align-middle">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $allocation->payment->payment_number }}</td>
                                    <td>Rp.{{ number_format($allocation->amount, 0, ',', '.') }},-</td>
                                    <td>{{ strtoupper($allocation->payment->method) }}</td>
                                    <td>
                                        @if ($allocation->status == 'APPROVED')
                                            <span class="badge text-bg-success">APPROVED</span>
                                        @elseif($allocation->status == 'REVIEWED')
                                            <span class="badge text-bg-info">REVIEWED</span>
                                        @elseif($allocation->status == 'REJECTED')
                                            <span class="badge text-bg-danger">REJECTED</span>
                                        @else
                                            <span class="badge text-bg-secondary">PENDING</span>
                                        @endif
                                    </td>
                                    <td>{{ $allocation->payment->paid_at }}</td>
                                    <td>
                                        @if ($allocation->payment->evidence_path)
                                            <a href="{{ asset('storage/' . $allocation->payment->evidence_path) }}"
                                                target="_blank" class="btn btn-sm btn-info m-1">VIEW EVIDENCE</a>
                                        @endif
                                        @can('role', ['admin'])
                                            @switch($allocation->status)
                                                @case('REVIEWED')
                                                    <form action="{{ route('contrib.admin.review', [$allocation->id, true]) }}"
                                                        method="post">
                                                        @csrf
                                                        <input type="submit" value="APPROVE" class="btn btn-sm btn-success m-1" />
                                                    </form>
                                                    <form action="{{ route('contrib.admin.review', [$allocation->id, false]) }}"
                                                        method="post">
                                                        @csrf
                                                        <input type="submit" value="REJECT" class="btn btn-sm btn-danger m-1" />
                                                    </form>
                                                @break

                                                @case('REJECTED')
                                                @case(null)
                                                    <form action="{{ route('contrib.admin.review', [$allocation->id, true]) }}"
                                                        method="post">
                                                        @csrf
                                                        <input type="submit" value="VERIFY" class="btn btn-sm btn-success m-1" />
                                                    </form>
                                                    <form action="{{ route('contrib.admin.review', [$allocation->id, false]) }}"
                                                        method="post">
                                                        @csrf
                                                        <input type="submit" value="REJECT" class="btn btn-sm btn-danger m-1" />
                                                    </form>
                                                @break

                                                @case('APPROVED')
                                                    <form action="{{ route('contrib.admin.review', [$allocation->id, false]) }}"
                                                        method="post">
                                                        @csrf
                                                        <input type="submit" value="REJECT" class="btn btn-sm btn-danger m-1" />
                                                    </form>
                                                @break
                                            @endswitch
                                        @endcan
                                        @if (empty($allocation->payment->status))
                                            <form action="{{ route('contrib.payments.destroy', [$allocation->payment->id]) }}"
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
                        @if(isset($payments))
                        <div class="text-center">
                            {{ $payments->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    @endsection
