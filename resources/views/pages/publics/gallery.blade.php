@extends('layouts.base-topbar')

@section('content')
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="fw-bold display-6 mb-2">Dokumentasi Kegiatan Club</h2>
                <p class="text-body-secondary lead fs-6">
                    Momen-momen keseruan dan catatan perjalanan kegiatan club. Arahkan kursor pada foto untuk melihat detail kegiatan.
                </p>
                <hr class="w-25 mx-auto text-primary border-2">
            </div>
        </div>

        <!-- Search Bar -->
        <div class="row justify-content-center mb-4">
            <div class="col-md-6">
                <form action="{{ route('public.gallery.index') }}" method="GET" class="d-flex gap-2">
                    <input type="text" name="filter" class="form-control" placeholder="Cari kegiatan atau momen..." value="{{ request('filter') }}">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                    @if (request()->filled('filter'))
                        <a href="{{ route('public.gallery.index') }}" class="btn btn-outline-secondary">Reset</a>
                    @endif
                </form>
            </div>
        </div>

        @if ($data->isEmpty())
            <div class="card card-outline card-secondary shadow-sm text-center p-5 my-4">
                <div class="card-body">
                    <i class="bi bi-images text-body-secondary fs-1 d-block mb-3"></i>
                    <h4 class="fw-bold">Belum Ada Dokumentasi</h4>
                    <p class="text-body-secondary">Dokumentasi foto kegiatan belum tersedia saat ini. Silakan kunjungi kembali nanti!</p>
                </div>
            </div>
        @else
            <!--begin::Gallery Grid-->
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-4 g-3 mb-4">
                @foreach ($data as $item)
                    @php
                        $eventTitle = $item->event?->post?->title ?? $item->title;
                        $description = $item->description ?: ($item->event?->post?->short_description ?? '');
                        $hoverTooltip = trim($eventTitle . ($description ? ' - ' . $description : ''));
                    @endphp
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0 overflow-hidden" title="{{ $hoverTooltip }}">
                            <a href="{{ $item->path_image }}" target="_blank" class="d-block ratio ratio-4x3 overflow-hidden" title="{{ $hoverTooltip }}">
                                <img src="{{ $item->path_image }}"
                                     class="card-img-top object-fit-cover w-100 h-100"
                                     alt="{{ $hoverTooltip }}"
                                     loading="lazy"
                                     style="transition: transform .3s ease;"
                                     onmouseover="this.style.transform='scale(1.05)'"
                                     onmouseout="this.style.transform='scale(1)'"
                                     title="{{ $hoverTooltip }}">
                            </a>
                            <div class="card-footer bg-white text-center py-2 px-2 border-0">
                                <small class="text-body-secondary fw-medium">
                                    <i class="bi bi-calendar3 me-1"></i>{{ $item->taken_date }}
                                </small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!--end::Gallery Grid-->

            <div class="d-flex justify-content-center">
                {{ $data->links() }}
            </div>
        @endif
    </div>
@endsection
