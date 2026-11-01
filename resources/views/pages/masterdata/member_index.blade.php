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

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i>{{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card mb-4 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-title fw-bold mb-0">List Member</h3>
                        @if (isset($upcomingEvents) && $upcomingEvents->isNotEmpty())
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle ms-2">
                                <i class="bi bi-calendar-event me-1"></i>{{ $upcomingEvents->count() }} Kegiatan Aktif / Mendatang
                            </span>
                        @endif
                    </div>
                    <div class="card-tools">
                        <a href="{{ route('master-data.member.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-person-plus me-1"></i>CREATE MEMBER
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Member Code</th>
                                <th>Member Name</th>
                                <th>Account Status</th>
                                <th>Phone / WhatsApp</th>
                                <th>Email Address</th>
                                <th>WhatsApp Notification</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $member)
                                @php
                                    $hasPhone = $member->hasValidWhatsAppNumber();
                                    $activeInvoices = $member->getActiveInvoices();
                                    $hasActiveIuran = $activeInvoices->isNotEmpty();
                                    $totalActiveIuran = $member->getTotalActiveIuranAmount();
                                    $eventsList = $upcomingEvents ?? collect();
                                    $hasUpcomingEvents = $eventsList->isNotEmpty();
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><a href="#" class="fw-bold text-decoration-none">{{ $member->member_code }}</a></td>
                                    <td>
                                        <div class="fw-semibold">{{ $member->full_name }}</div>
                                        <small class="text-muted">Terdaftar: {{ $member->created_at }}</small>
                                    </td>
                                    <td>
                                        @if ($member->user && $member->user->isProfileComplete())
                                            <span class="badge text-bg-success">Complete</span>
                                        @elseif ($member->user)
                                            <span class="badge text-bg-warning">Pending Details</span>
                                        @else
                                            <span class="badge text-bg-danger">Need Account</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($hasPhone)
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-whatsapp text-success me-1"></i>
                                                <span>{{ $member->masked_phone_number }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted fst-italic">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($member->user && $member->user->email)
                                            {{ $member->user->masked_email }}
                                        @else
                                            <span class="text-body-secondary fst-italic">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1 align-items-center">
                                            {{-- Button Notify Iuran --}}
                                            @if (! $hasPhone)
                                                <button type="button" class="btn btn-outline-secondary btn-sm" disabled title="Nomor WhatsApp belum terdaftar">
                                                    <i class="bi bi-whatsapp"></i> Iuran
                                                </button>
                                            @elseif ($hasActiveIuran)
                                                <a href="{{ route('master-data.member.notify-iuran', $member->id) }}"
                                                   target="_blank"
                                                   class="btn btn-success btn-sm text-nowrap"
                                                   title="Kirim notifikasi {{ $activeInvoices->count() }} tagihan iuran aktif (Rp {{ number_format($totalActiveIuran, 0, ',', '.') }}) ke WhatsApp member">
                                                    <i class="bi bi-whatsapp"></i> Iuran
                                                    <span class="badge bg-danger rounded-pill ms-1">{{ $activeInvoices->count() }}</span>
                                                </a>
                                            @else
                                                <button type="button" class="btn btn-outline-secondary btn-sm text-nowrap" disabled title="Tidak ada tagihan iuran aktif (Lunas)">
                                                    <i class="bi bi-whatsapp"></i> Iuran
                                                    <span class="badge bg-secondary-subtle text-secondary-emphasis rounded-pill ms-1">Lunas</span>
                                                </button>
                                            @endif

                                            {{-- Button Notify Event --}}
                                            @if (! $hasPhone)
                                                <button type="button" class="btn btn-outline-secondary btn-sm" disabled title="Nomor WhatsApp belum terdaftar">
                                                    <i class="bi bi-whatsapp"></i> Event
                                                </button>
                                            @elseif (! $hasUpcomingEvents)
                                                <button type="button" class="btn btn-outline-secondary btn-sm text-nowrap" disabled title="Tidak ada kegiatan aktif / mendatang">
                                                    <i class="bi bi-whatsapp"></i> Event
                                                </button>
                                            @elseif ($eventsList->count() === 1)
                                                @php $singleEvent = $eventsList->first(); @endphp
                                                <a href="{{ route('master-data.member.notify-event', ['member' => $member->id, 'event' => $singleEvent->id]) }}"
                                                   target="_blank"
                                                   class="btn btn-primary btn-sm text-nowrap"
                                                   title="Kirim notifikasi kegiatan '{{ $singleEvent->post?->title }}' ke WhatsApp member">
                                                    <i class="bi bi-whatsapp"></i> Event
                                                </a>
                                            @else
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle text-nowrap" data-bs-toggle="dropdown" aria-expanded="false" title="Pilih kegiatan untuk notifikasi WhatsApp">
                                                        <i class="bi bi-whatsapp"></i> Event ({{ $eventsList->count() }})
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow">
                                                        <li class="dropdown-header fw-bold text-uppercase small">Pilih Kegiatan:</li>
                                                        @foreach ($eventsList as $eventItem)
                                                            <li>
                                                                <a class="dropdown-item py-2" href="{{ route('master-data.member.notify-event', ['member' => $member->id, 'event' => $eventItem->id]) }}" target="_blank">
                                                                    <div class="fw-semibold text-truncate" style="max-width: 250px;">
                                                                        <i class="bi bi-calendar-check text-primary me-1"></i>{{ $eventItem->post?->title }}
                                                                    </div>
                                                                    <small class="text-muted d-block ps-4"><i class="bi bi-clock me-1"></i>{{ $eventItem->short_event_period }}</small>
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ route('master-data.member.edit', [$member->id]) }}" class="btn btn-info btn-sm me-1 text-white" title="Edit Member">
                                                <i class="bi bi-pencil-square"></i> EDIT
                                            </a>
                                            <form action="{{ route('master-data.member.delete', [$member->id]) }}" method="post" onsubmit="return confirm('Apakah Anda yakin ingin menghapus member ini?');">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus Member">
                                                    <i class="bi bi-trash"></i> DELETE
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i class="bi bi-people fs-2 d-block mb-2"></i>
                                        Belum ada data member.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="p-3">
                        {{ $data->links() }}
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
