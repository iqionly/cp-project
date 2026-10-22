@extends('layouts.base-sidebar')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Generate Invoice Members</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('invoice.generate') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="invoice_id">Select Journal</label>
                            <select name="journal" id="journal"
                                class="form-control @error('journal') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($journals as $journal)
                                    <option value="{{ $journal->id }}">
                                        {{ $journal->name }} - Default: Rp.{{ $journal->default_amount }},-
                                    </option>
                                @endforeach
                            </select>
                            @error('journal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="amount">Payment Amount</label>
                            <input type="text" name="amount" id="amount"
                                class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}"
                                placeholder="e.g. 50000 or leave empty for journal default amount">
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="period_start">Period Start</label>
                            <input type="date" class="form-control @error('period_start') is-invalid @enderror" name="period_start" id="period_start" />
                            @error('period_start')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="due_at">Due At</label>
                            <input type="date" class="form-control @error('due_at') is-invalid @enderror" name="due_at" id="due_at" />
                            @error('due_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="notes">Notes</label>
                            <textarea class="form-control" name="notes" id="notes" rows="5"></textarea>
                            @error('notes')
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
    </div>
@endsection
