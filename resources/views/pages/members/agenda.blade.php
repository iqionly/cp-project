@extends('layouts.base-sidebar')

@section('content')
    <div class="row">
        <div class="col-12">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i> {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card card-outline card-primary mb-4 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title fw-bold mb-0">
                        <i class="bi bi-calendar2-week me-2 text-primary"></i>Agenda & Kalender Kegiatan Club
                    </h3>
                    <div class="card-tools">
                        <span class="badge text-bg-primary">{{ $events->total() }} Total Kegiatan</span>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-body-secondary mb-4">
                        Berikut adalah daftar kegiatan dan agenda club mendatang maupun yang sedang berlangsung. Anda dapat melihat detail kegiatan serta mendaftarkan diri secara langsung.
                    </p>

                    @if ($events->isEmpty())
                        <div class="text-center py-5 text-body-secondary">
                            <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
                            <h5>Belum ada agenda kegiatan saat ini</h5>
                            <p class="small">Silakan cek kembali di lain waktu.</p>
                        </div>
                    @else
                        <div class="row g-4">
                            @foreach ($events as $event)
                                @php
                                    $isRegistered = in_array($event->id, $myRegisteredEventIds);
                                    $isFull = $event->max_attendant > 0 && $event->registrations_count >= $event->max_attendant;
                                @endphp
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100 shadow-sm border {{ $isRegistered ? 'border-success' : '' }}">
                                        @if ($event->post && $event->post->path_featured_image)
                                            <img src="{{ $event->post->path_featured_image }}" class="card-img-top object-fit-cover" style="height: 180px;" alt="{{ $event->post->title }}">
                                        @else
                                            <div class="bg-body-secondary d-flex align-items-center justify-content-center" style="height: 180px;">
                                                <i class="bi bi-calendar-event text-secondary fs-1"></i>
                                            </div>
                                        @endif

                                        <div class="card-body d-flex flex-column">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h5 class="card-title fw-bold text-truncate mb-0" title="{{ $event->post?->title }}">
                                                    {{ $event->post?->title ?? 'Kegiatan Tanpa Judul' }}
                                                </h5>
                                                @if ($isRegistered)
                                                    <span class="badge bg-success ms-2 text-nowrap"><i class="bi bi-check2-circle me-1"></i>Terdaftar</span>
                                                @elseif ($isFull)
                                                    <span class="badge bg-danger ms-2 text-nowrap">Penuh</span>
                                                @else
                                                    <span class="badge bg-info ms-2 text-nowrap">Buka</span>
                                                @endif
                                            </div>

                                            <p class="card-text text-body-secondary small mb-3 flex-grow-1">
                                                {{ $event->post?->short_description ?? '-' }}
                                            </p>

                                            <ul class="list-unstyled small mb-3">
                                                <li class="mb-1 text-truncate">
                                                    <i class="bi bi-calendar-event me-2 text-primary"></i>
                                                    <strong>Waktu:</strong> {{ $event->short_event_period }}
                                                </li>
                                                <li class="mb-1 text-truncate">
                                                    <i class="bi bi-geo-alt me-2 text-danger"></i>
                                                    <strong>Lokasi:</strong> {{ $event->location }}
                                                </li>
                                                <li class="mb-1">
                                                    <i class="bi bi-people me-2 text-warning"></i>
                                                    <strong>Peserta:</strong> {{ $event->registrations_count }} / {{ $event->max_attendant > 0 ? $event->max_attendant.' orang' : 'Tidak Terbatas' }}
                                                </li>
                                            </ul>

                                            <div class="pt-2 border-top mt-auto d-flex justify-content-between align-items-center">
                                                <a href="{{ route('public.events.show', $event) }}" class="btn btn-outline-secondary btn-sm" target="_blank">
                                                    <i class="bi bi-eye me-1"></i> Detail
                                                </a>

                                                @if ($isRegistered)
                                                    <form action="{{ route('member.agenda.unregister', $event) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pendaftaran kegiatan ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                                            <i class="bi bi-x-circle me-1"></i> Batal Daftar
                                                        </button>
                                                    </form>
                                                @elseif ($isFull)
                                                    <button type="button" class="btn btn-secondary btn-sm" disabled>
                                                        Kuota Penuh
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#registerModal{{ $event->id }}">
                                                        <i class="bi bi-pencil-square me-1"></i> Registrasi
                                                    </button>

                                                    <!-- Register Modal -->
                                                    <div class="modal fade" id="registerModal{{ $event->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $event->id }}" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <form action="{{ route('member.agenda.register', $event) }}" method="POST">
                                                                    @csrf
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="modalLabel{{ $event->id }}">Registrasi Kegiatan</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-content-body p-3">
                                                                        <p class="mb-2">Konfirmasi pendaftaran untuk kegiatan:</p>
                                                                        <div class="alert alert-light border">
                                                                            <h6 class="fw-bold mb-1">{{ $event->post?->title }}</h6>
                                                                            <small class="d-block text-secondary"><i class="bi bi-calendar3 me-1"></i>{{ $event->short_event_period }}</small>
                                                                            <small class="d-block text-secondary"><i class="bi bi-geo-alt me-1"></i>{{ $event->location }}</small>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label for="notes{{ $event->id }}" class="form-label">Catatan Tambahan (Opsional):</label>
                                                                            <textarea name="notes" id="notes{{ $event->id }}" class="form-control" rows="3" placeholder="Contoh: membawa kendaraan sendiri, preferensi regu, dsb."></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                                        <button type="submit" class="btn btn-primary">Konfirmasi Daftar</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            {{ $events->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
