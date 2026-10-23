@extends('layouts.base-sidebar')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@4.7.0/dist/apexcharts.css" crossorigin="anonymous" />
@endpush

@section('content')
    @if (request()->context()?->role_grouping === 'admin')
        {{-- ===== ADMIN DASHBOARD ===== --}}

        {{-- Year filter --}}
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-end">
                <form method="GET"
                    action="{{ route('home', ['context' => request()->context()->role_grouping]) }}"
                    class="d-flex align-items-center gap-2">
                    <label for="yearSelect" class="form-label mb-0 fw-semibold">Year:</label>
                    <select id="yearSelect" name="year" class="form-select form-select-sm" style="width:auto"
                        onchange="this.form.submit()">
                        @foreach ($availableYears as $yr)
                            <option value="{{ $yr }}" @selected($yr == $selectedYear)>{{ $yr }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        {{-- Stat cards --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                            <i class="bi bi-people-fill fs-4 text-primary"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Total Members</div>
                            <div class="fs-4 fw-bold">{{ number_format($totalMembers) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="bi bi-file-earmark-text-fill fs-4 text-info"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Total Billed {{ $selectedYear }}</div>
                            <div class="fs-6 fw-bold">Rp {{ number_format($totalBilledThisYear, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="bi bi-cash-stack fs-4 text-success"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Collected {{ $selectedYear }}</div>
                            <div class="fs-6 fw-bold">Rp {{ number_format($totalPaidThisYear, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                            <i class="bi bi-exclamation-triangle-fill fs-4 text-danger"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Outstanding {{ $selectedYear }}</div>
                            <div class="fs-6 fw-bold">Rp {{ number_format($totalOutstandingThisYear, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Monthly amount stacked + invoice status donut --}}
        <div class="row g-3 mb-3">
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-bar-chart-fill text-primary me-1"></i>
                            Monthly Collection vs Outstanding — {{ $selectedYear }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="monthlyAmountChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-pie-chart-fill text-warning me-1"></i>
                            Invoice Status — {{ $selectedYear }}
                        </h5>
                    </div>
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <div id="statusDonutChart" class="w-100"></div>
                        <div class="d-flex justify-content-center gap-3 mt-2 flex-wrap small">
                            <span><i class="bi bi-circle-fill text-success me-1"></i>Fully Paid
                                <strong>({{ $fullyPaidCount }})</strong></span>
                            <span><i class="bi bi-circle-fill text-warning me-1"></i>Partial
                                <strong>({{ $partialCount }})</strong></span>
                            <span><i class="bi bi-circle-fill text-danger me-1"></i>Not Yet
                                <strong>({{ $notYetCount }})</strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Monthly invoice count + yearly overview --}}
        <div class="row g-3">
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-receipt text-secondary me-1"></i>
                            Invoice Count by Status — {{ $selectedYear }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="monthlyCountChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-graph-up-arrow text-success me-1"></i>
                            Billed vs Collected — All Years
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="yearlyChart"></div>
                    </div>
                </div>
            </div>
        </div>

    @else
        {{-- ===== NON-ADMIN PLACEHOLDER ===== --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Welcome</h3>
                    </div>
                    <div class="card-body">
                        Hello, {{ auth()->user()?->name }}! Use the menu to navigate.
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@4.7.0/dist/apexcharts.min.js" crossorigin="anonymous"></script>

    @if (request()->context()?->role_grouping === 'admin')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                const idr = val => 'Rp ' + Number(val).toLocaleString('id-ID');

                // ── 1. Monthly stacked amount chart (Paid vs Outstanding) ──────
                new ApexCharts(document.querySelector('#monthlyAmountChart'), {
                    chart: {
                        type: 'bar',
                        height: 280,
                        stacked: true,
                        toolbar: { show: false },
                    },
                    series: [
                        { name: 'Paid', data: @json($monthlyPaid) },
                        { name: 'Outstanding', data: @json($monthlyOutstanding) },
                    ],
                    xaxis: { categories: months },
                    yaxis: { labels: { formatter: idr } },
                    tooltip: { y: { formatter: idr } },
                    colors: ['#198754', '#dc3545'],
                    dataLabels: { enabled: false },
                    plotOptions: { bar: { borderRadius: 3, columnWidth: '60%' } },
                    legend: { position: 'top' },
                }).render();

                // ── 2. Invoice status donut ────────────────────────────────────
                const fullyPaid = {{ $fullyPaidCount }};
                const partial   = {{ $partialCount }};
                const notYet    = {{ $notYetCount }};
                const total     = fullyPaid + partial + notYet;

                new ApexCharts(document.querySelector('#statusDonutChart'), {
                    chart: { type: 'donut', height: 240 },
                    series: [fullyPaid, partial, notYet],
                    labels: ['Fully Paid', 'Partially Paid', 'Not Yet Paid'],
                    colors: ['#198754', '#ffc107', '#dc3545'],
                    dataLabels: { enabled: total > 0 },
                    legend: { show: false },
                    tooltip: {
                        y: { formatter: val => val + ' invoice(s)' }
                    },
                    plotOptions: {
                        pie: { donut: { size: '65%', labels: {
                            show: total > 0,
                            total: {
                                show: true,
                                label: 'Invoices',
                                formatter: () => total,
                            }
                        }}}
                    },
                }).render();

                // ── 3. Monthly invoice count grouped bar ───────────────────────
                new ApexCharts(document.querySelector('#monthlyCountChart'), {
                    chart: {
                        type: 'bar',
                        height: 280,
                        toolbar: { show: false },
                    },
                    series: [
                        { name: 'Fully Paid', data: @json($monthlyFullyPaid) },
                        { name: 'Partially Paid', data: @json($monthlyPartial) },
                        { name: 'Not Yet Paid', data: @json($monthlyNotYet) },
                    ],
                    xaxis: { categories: months },
                    yaxis: { labels: { formatter: val => Math.round(val) } },
                    tooltip: { y: { formatter: val => val + ' invoice(s)' } },
                    colors: ['#198754', '#ffc107', '#dc3545'],
                    dataLabels: { enabled: false },
                    plotOptions: { bar: { borderRadius: 3, columnWidth: '75%' } },
                    legend: { position: 'top' },
                }).render();

                // ── 4. Yearly billed vs paid line chart ───────────────────────
                const yearlyRaw    = @json($yearlyBilled);
                const yearlyPaid   = @json($yearlyPaid);
                const yearKeys     = Object.keys(yearlyRaw);

                new ApexCharts(document.querySelector('#yearlyChart'), {
                    chart: { type: 'line', height: 280, toolbar: { show: false } },
                    series: [
                        { name: 'Total Billed', data: yearKeys.map(y => yearlyRaw[y] ?? 0) },
                        { name: 'Total Collected', data: yearKeys.map(y => yearlyPaid[y] ?? 0) },
                    ],
                    xaxis: { categories: yearKeys },
                    yaxis: { labels: { formatter: idr } },
                    tooltip: { y: { formatter: idr } },
                    colors: ['#0d6efd', '#198754'],
                    stroke: { curve: 'smooth', width: 3 },
                    markers: { size: 5 },
                    dataLabels: { enabled: false },
                    legend: { position: 'top' },
                }).render();
            });
        </script>
    @endif
@endpush
