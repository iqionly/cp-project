@extends('layouts.base-sidebar')

@section('content')
    <div class="row">
        <div class="col-12">

            <div class="card card-outline card-secondary mb-4 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title fw-bold mb-0">
                        <i class="bi bi-clock-history me-2 text-secondary"></i>Riwayat & Arsip Kegiatan Club
                    </h3>
                    <div class="card-tools">
                        <span class="badge text-bg-secondary">{{ $events->total() }} Kegiatan Selesai</span>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-body-secondary mb-4">
                        Daftar riwayat kegiatan dan agenda club yang telah terlaksana. Anda dapat meninjau kembali catatan kegiatan, partisipasi peserta, serta dokumentasi foto kegiatan yang telah diarsipkan.
                    </p>

                    @if ($events->isEmpty())
                        <div class="text-center py-5 text-body-secondary">
                            <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
                            <h5>Belum ada riwayat kegiatan</h5>
                            <p class="small">Kegiatan yang telah lewat masa berlakunya akan otomatis muncul di halaman arsip ini.</p>
                        </div>
                    @else
                        <div class="row g-4">
                            @foreach ($events as $event)
                                @php
                                    $isRegistered = in_array($event->id, $myRegisteredEventIds);
                                    $galleryCount = $event->galleries->count();
                                @endphp
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100 shadow-sm border {{ $isRegistered ? 'border-primary' : '' }}">
                                        @if ($event->post && $event->post->path_featured_image)
                                            <img src="{{ $event->post->path_featured_image }}" class="card-img-top object-fit-cover" style="height: 180px;" alt="{{ $event->post->title }}">
                                        @else
                                            <div class="bg-body-secondary d-flex align-items-center justify-content-center" style="height: 180px;">
                                                <i class="bi bi-calendar-check text-secondary fs-1"></i>
                                            </div>
                                        @endif

                                        <div class="card-body d-flex flex-column">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h5 class="card-title fw-bold text-truncate mb-0" title="{{ $event->post?->title }}">
                                                    {{ $event->post?->title ?? 'Kegiatan Tanpa Judul' }}
                                                </h5>
                                                <span class="badge bg-secondary ms-2 text-nowrap">Selesai</span>
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
                                                    <strong>Total Peserta:</strong> {{ $event->registrations_count }} orang
                                                </li>
                                                @if ($isRegistered)
                                                    <li class="mb-1 text-success">
                                                        <i class="bi bi-check-circle-fill me-2"></i>
                                                        <strong>Status Anda:</strong> Terdaftar / Diikuti
                                                    </li>
                                                @endif
                                            </ul>

                                            <div class="pt-2 border-top mt-auto d-flex justify-content-between align-items-center">
                                                <a href="{{ route('public.events.show', $event) }}" class="btn btn-outline-secondary btn-sm" target="_blank">
                                                    <i class="bi bi-info-circle me-1"></i> Detail
                                                </a>

                                                @if ($galleryCount > 0)
                                                    <a href="{{ route('public.gallery.index', ['event_id' => $event->id]) }}" class="btn btn-outline-success btn-sm">
                                                        <i class="bi bi-images me-1"></i> Foto ({{ $galleryCount }})
                                                    </a>
                                                @else
                                                    <span class="text-body-secondary small">
                                                        <i class="bi bi-clock me-1"></i> Telah Berakhir
                                                    </span>
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
