@extends('layouts.base-topbar')

@section('content')
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="fw-bold display-6 mb-2">Merchandise Resmi Club</h2>
                <p class="text-body-secondary lead fs-6">
                    Dapatkan official merchandise dan atribut resmi club untuk mendukung kegiatan dan kebanggaan bersama.
                </p>
                <hr class="w-25 mx-auto text-primary border-2">
            </div>
        </div>

        @if ($data->isEmpty())
            <div class="card card-outline card-secondary shadow-sm text-center p-5 my-4">
                <div class="card-body">
                    <i class="bi bi-bag-x text-body-secondary fs-1 d-block mb-3"></i>
                    <h4 class="fw-bold">Belum Ada Merchandise</h4>
                    <p class="text-body-secondary">Katalog merchandise saat ini belum tersedia. Silakan kunjungi kembali nanti!</p>
                </div>
            </div>
        @else
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 mb-4">
                @foreach ($data as $item)
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0 transition-hover">
                            <div class="position-relative">
                                @if ($item->path_image)
                                    <img src="{{ $item->path_image }}" class="card-img-top object-fit-cover" style="height: 220px;" alt="{{ $item->name }}">
                                @else
                                    <div class="bg-body-secondary d-flex align-items-center justify-content-center" style="height: 220px;">
                                        <i class="bi bi-bag text-secondary fs-1"></i>
                                    </div>
                                @endif

                                @if ($item->stock <= 0)
                                    <span class="position-absolute top-0 end-0 badge bg-danger m-2">Habis</span>
                                @else
                                    <span class="position-absolute top-0 end-0 badge bg-success m-2">Stok: {{ $item->stock }}</span>
                                @endif
                            </div>

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold mb-1 fs-6">{{ $item->name }}</h5>
                                <div class="text-primary fw-bold fs-5 mb-2">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </div>
                                <p class="card-text text-body-secondary small flex-grow-1">
                                    {{ Str::limit($item->description, 100) }}
                                </p>
                            </div>

                            <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                                <div class="d-grid">
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#merchModal{{ $item->id }}">
                                        <i class="bi bi-info-circle me-1"></i> Lihat Detail
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Detail -->
                        <div class="modal fade" id="merchModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">{{ $item->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        @if ($item->path_image)
                                            <img src="{{ $item->path_image }}" class="img-fluid rounded mb-3 w-100 object-fit-cover" style="max-height: 300px;" alt="{{ $item->name }}">
                                        @endif
                                        <h4 class="text-primary fw-bold mb-2">Rp {{ number_format($item->price, 0, ',', '.') }}</h4>
                                        <div class="mb-3">
                                            <span class="badge {{ $item->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                                {{ $item->stock > 0 ? 'Stok Tersedia: '.$item->stock.' unit' : 'Stok Habis' }}
                                            </span>
                                        </div>
                                        <div class="text-body mb-3">
                                            <h6 class="fw-bold mb-1">Deskripsi Produk:</h6>
                                            <p class="text-body-secondary whitespace-pre-line">{{ $item->description ?? 'Tidak ada deskripsi rinci.' }}</p>
                                        </div>
                                        <div class="alert alert-light border small text-body-secondary">
                                            <i class="bi bi-whatsapp text-success me-1"></i> Untuk pemesanan merchandise, silakan hubungi pengurus atau melalui sekretariat club saat kegiatan berlangsung.
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center">
                {{ $data->links() }}
            </div>
        @endif
    </div>
@endsection
