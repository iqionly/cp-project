@extends('layouts.base-topbar')

@php
    static $contentClass = 'container py-3';
@endphp

@section('content')
<div class="d-flex flex-column gap-5 mb-5">

    {{-- ======================================================== --}}
    {{-- 1. BANNER UTAMA                                          --}}
    {{-- ======================================================== --}}
    <section id="banner" class="banner-section">
        <div class="card border-0 shadow-sm overflow-hidden rounded-4">
            <div class="card-body p-0 position-relative">
                <img src="{{ url('assets/banner1.jpg') }}"
                     alt="Banner District Tiger Cikarang PLUS"
                     class="w-100 object-fit-cover rounded-4"
                     style="max-height: 520px; min-height: 220px;" />
            </div>
        </div>
    </section>

    {{-- ======================================================== --}}
    {{-- 2. SEJARAH SINGKAT                                       --}}
    {{-- ======================================================== --}}
    <section id="sejarah" class="sejarah-section">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-4 p-md-5">
                <div class="row align-items-center g-4">
                    <div class="col-lg-4 text-center text-lg-start">
                        <span class="badge bg-primary-subtle text-primary px-3 py-2  fw-semibold mb-2">
                            <i class="bi bi-clock-history me-1"></i> Sejarah & Profil
                        </span>
                        <h2 class="fw-bold display-6 mb-3">Sejarah Club</h2>
                        <div class="d-flex gap-2 justify-content-center justify-content-lg-start mb-3">
                            <img src="{{ url('assets/logo1.jpeg') }}" alt="Logo DTC 1" width="55" height="55" class="rounded-circle shadow-sm" />
                            <img src="{{ url('assets/logo2.jpeg') }}" alt="Logo DTC 2" width="55" height="55" class="rounded-circle shadow-sm" />
                        </div>
                        <p class="text-body-secondary small mb-0">
                            District Tiger Cikarang PLUS (DTC PLUS) &bull; Est. 2004
                        </p>
                    </div>

                    <div class="col-lg-8 border-start-lg ps-lg-4">
                        @if ($profilePost && !empty($profilePost->description))
                            <div class="text-body leading-relaxed fs-6 mb-4" style="line-height: 1.8;">
                                {!! nl2br(e(Str::limit($profilePost->description, 450))) !!}
                            </div>
                        @else
                            <div class="text-body leading-relaxed fs-6 mb-4" style="line-height: 1.8;">
                                <p class="mb-3">
                                    <strong>District Tiger Cikarang PLUS</strong> adalah organisasi club kendaraan bermotor yang bertempat di Kabupaten Bekasi. Seperti tertera pada namanya, club ini menjadikan motor Honda Tiger sebagai tunggangan utamanya. DTC PLUS memiliki basecamp di Ruko Kuning Taman Sentosa dan sekretariat di Kompas Tambun. Club ini tergabung dalam wadah Honda Tiger Club Indonesia sejak Oktober 2004.
                                </p>
                                <p class="mb-0">
                                    DTC PLUS didirikan untuk menyatukan anggota serta menyalurkan hobi otomotif dengan mengutamakan rasa kekeluargaan, kesetiakawanan, serta disiplin tertib berlalu lintas.
                                </p>
                            </div>
                        @endif

                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <a href="{{ $profileUrl }}" class="btn btn-primary px-4 py-2  fw-medium shadow-sm">
                                Lihat Selengkapnya <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                            <span class="text-body-secondary small">
                                <i class="bi bi-info-circle me-1"></i> Baca kisah berdirinya club, visi, dan misi lengkap
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ======================================================== --}}
    {{-- 3. STRUKTUR PENGURUS                                     --}}
    {{-- ======================================================== --}}
    <section id="struktur-pengurus" class="struktur-section">
        <div class="text-center mb-4">
            <span class="badge bg-secondary-subtle text-secondary px-3 py-2  fw-semibold mb-2">
                <i class="bi bi-diagram-3 me-1"></i> Organisasi
            </span>
            <h2 class="fw-bold display-6 mb-2">Struktur Pengurus</h2>
            <p class="text-body-secondary lead fs-6 mx-auto" style="max-width: 680px;">
                {{ $strukturPost->description ?? 'Susunan pengurus dan bagan organisasi District Tiger Cikarang PLUS yang mengemban amanah kebersamaan dan kemajuan club.' }}
            </p>
            <hr class="w-25 mx-auto text-primary border-2 mt-2">
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-3 p-md-4 text-center">
                @php
                    $strukturImage = null;
                    if ($strukturPost && $strukturPost->getRawOriginal('path_featured_image')) {
                        $strukturImage = $strukturPost->path_featured_image;
                    } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists('galleries/struktur-anggota.png')) {
                        $strukturImage = url('storage/galleries/struktur-anggota.png');
                    }
                @endphp

                @if ($strukturImage)
                    <div class="position-relative d-inline-block w-100">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalStrukturPengurus" class="d-block cursor-zoom" title="Klik untuk memperbesar struktur pengurus">
                            <img src="{{ $strukturImage }}"
                                 alt="Struktur Pengurus DTC PLUS"
                                 class="img-fluid rounded-3 border w-100 shadow-sm"
                                 style="max-height: 600px; object-fit: contain; background: #fff;" />
                        </a>
                        <div class="mt-3">
                            <button type="button" class="btn btn-outline-secondary btn-sm  px-3" data-bs-toggle="modal" data-bs-target="#modalStrukturPengurus">
                                <i class="bi bi-arrows-fullscreen me-1"></i> Perbesar Bagan Struktur
                            </button>
                        </div>
                    </div>

                    <!-- Modal Pembesar Struktur Pengurus -->
                    <div class="modal fade" id="modalStrukturPengurus" tabindex="-1" aria-labelledby="modalStrukturTitle" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-centered">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-bold" id="modalStrukturTitle">
                                        <i class="bi bi-diagram-3 text-primary me-2"></i>Bagan Struktur Pengurus DTC PLUS
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center p-3 p-md-4">
                                    <img src="{{ $strukturImage }}" alt="Struktur Pengurus DTC PLUS Full" class="img-fluid rounded w-100" />
                                </div>
                                <div class="modal-footer border-0 pt-0">
                                    <a href="{{ $strukturImage }}" target="_blank" class="btn btn-outline-primary btn-sm ">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Buka Gambar di Tab Baru
                                    </a>
                                    <button type="button" class="btn btn-secondary btn-sm " data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="p-5 text-center text-body-secondary">
                        <i class="bi bi-image fs-1 d-block mb-2"></i>
                        <p class="mb-0">Bagan struktur pengurus belum diunggah.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- ======================================================== --}}
    {{-- 4. DOKUMENTASI (4-6 Foto Terbaru + "Lihat Semua")          --}}
    {{-- ======================================================== --}}
    <section id="dokumentasi" class="dokumentasi-section">
        <div class="text-center mb-4">
            <span class="badge bg-success-subtle text-success px-3 py-2  fw-semibold mb-2">
                <i class="bi bi-camera me-1"></i> Galeri Kegiatan
            </span>
            <h2 class="fw-bold display-6 mb-2">Dokumentasi Club</h2>
            <p class="text-body-secondary lead fs-6 mx-auto" style="max-width: 680px;">
                Momen-momen keseruan dan catatan perjalanan touring serta agenda sosial club terbaru.
            </p>
            <hr class="w-25 mx-auto text-primary border-2 mt-2">
        </div>

        @if ($galleries->isEmpty())
            <div class="card border-0 shadow-sm rounded-4 text-center p-5">
                <div class="card-body">
                    <i class="bi bi-images text-body-secondary fs-1 d-block mb-3"></i>
                    <h5 class="fw-bold">Belum Ada Dokumentasi</h5>
                    <p class="text-body-secondary mb-0">Dokumentasi foto kegiatan akan segera diunggah saat kegiatan berlangsung.</p>
                </div>
            </div>
        @else
            <div class="row row-cols-2 row-cols-md-3 g-3 g-md-4 mb-4">
                @foreach ($galleries as $gallery)
                    @php
                        $eventTitle = $gallery->event?->post?->title ?? $gallery->title;
                        $description = $gallery->description ?: ($gallery->event?->post?->short_description ?? '');
                        $tooltipText = trim($eventTitle . ($description ? ' - ' . $description : ''));
                    @endphp
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden" title="{{ $tooltipText }}">
                            <a href="{{ $gallery->path_image }}" target="_blank" class="d-block ratio ratio-4x3 overflow-hidden" title="{{ $tooltipText }}">
                                <img src="{{ $gallery->path_image }}"
                                     class="card-img-top object-fit-cover w-100 h-100"
                                     alt="{{ $tooltipText }}"
                                     loading="lazy"
                                     style="transition: transform .3s ease;"
                                     onmouseover="this.style.transform='scale(1.05)'"
                                     onmouseout="this.style.transform='scale(1)'" />
                            </a>
                            <div class="card-body p-2 p-md-3">
                                <h6 class="fw-semibold text-truncate mb-1 small" title="{{ $eventTitle }}">
                                    {{ $eventTitle }}
                                </h6>
                                <small class="text-body-secondary d-flex align-items-center">
                                    <i class="bi bi-calendar3 me-1"></i> {{ $gallery->taken_date }}
                                </small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center">
                <a href="{{ route('public.gallery.index') }}" class="btn btn-primary px-4 py-2  fw-medium shadow-sm">
                    Lihat Semua Dokumentasi <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        @endif
    </section>

    {{-- ======================================================== --}}
    {{-- 5. MERCHANDISE (Preview 4 Item + "Lihat Semua")           --}}
    {{-- ======================================================== --}}
    @if ($merchandises->isNotEmpty())
    <section id="merchandise" class="merchandise-section">
        <div class="text-center mb-4">
            <span class="badge bg-warning-subtle text-warning-emphasis px-3 py-2  fw-semibold mb-2">
                <i class="bi bi-bag-check me-1"></i> Official Store
            </span>
            <h2 class="fw-bold display-6 mb-2">Merchandise Resmi Club</h2>
            <p class="text-body-secondary lead fs-6 mx-auto" style="max-width: 680px;">
                Dapatkan atribut resmi dan official merchandise DTC PLUS untuk kebanggaan bersama.
            </p>
            <hr class="w-25 mx-auto text-primary border-2 mt-2">
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4 mb-4">
            @foreach ($merchandises as $item)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden d-flex flex-column">
                        <div class="position-relative">
                            @if ($item->path_image)
                                <img src="{{ $item->path_image }}" class="card-img-top object-fit-cover" style="height: 200px;" alt="{{ $item->name }}">
                            @else
                                <div class="bg-body-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="bi bi-bag text-secondary fs-1"></i>
                                </div>
                            @endif

                            @if ($item->stock <= 0)
                                <span class="position-absolute top-0 end-0 badge bg-danger m-2">Habis</span>
                            @else
                                <span class="position-absolute top-0 end-0 badge bg-success m-2">Stok: {{ $item->stock }}</span>
                            @endif
                        </div>

                        <div class="card-body p-3 d-flex flex-column flex-grow-1">
                            <h6 class="card-title fw-bold mb-1 text-truncate" title="{{ $item->name }}">{{ $item->name }}</h6>
                            <div class="text-primary fw-bold mb-2">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </div>
                            <p class="card-text text-body-secondary small flex-grow-1 mb-3">
                                {{ Str::limit($item->description, 80) }}
                            </p>
                            <button type="button" class="btn btn-outline-primary btn-sm  w-100" data-bs-toggle="modal" data-bs-target="#merchHomeModal{{ $item->id }}">
                                <i class="bi bi-info-circle me-1"></i> Detail
                            </button>
                        </div>
                    </div>

                    <!-- Modal Detail Merchandise -->
                    <div class="modal fade" id="merchHomeModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold">{{ $item->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    @if ($item->path_image)
                                        <img src="{{ $item->path_image }}" class="img-fluid rounded mb-3 w-100 object-fit-cover" style="max-height: 260px;" alt="{{ $item->name }}">
                                    @endif
                                    <h4 class="text-primary fw-bold mb-2">Rp {{ number_format($item->price, 0, ',', '.') }}</h4>
                                    <div class="mb-3">
                                        <span class="badge {{ $item->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $item->stock > 0 ? 'Stok Tersedia: '.$item->stock.' unit' : 'Stok Habis' }}
                                        </span>
                                    </div>
                                    <div class="mb-3">
                                        <h6 class="fw-bold mb-1">Deskripsi:</h6>
                                        <p class="text-body-secondary small mb-0">{{ $item->description ?? 'Tidak ada deskripsi rinci.' }}</p>
                                    </div>
                                    <div class="alert alert-light border small text-body-secondary mb-0">
                                        <i class="bi bi-whatsapp text-success me-1"></i> Hubungi pengurus club saat kopdar / kegiatan untuk pemesanan merchandise.
                                    </div>
                                </div>
                                <div class="modal-footer border-0">
                                    <a href="{{ route('public.merchandise.index') }}" class="btn btn-outline-primary btn-sm ">
                                        Lihat Semua Katalog
                                    </a>
                                    <button type="button" class="btn btn-secondary btn-sm " data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center">
            <a href="{{ route('public.merchandise.index') }}" class="btn btn-outline-primary px-4 py-2  fw-medium shadow-sm">
                Lihat Semua Merchandise <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </section>
    @endif

</div>
@endsection
