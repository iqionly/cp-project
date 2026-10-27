@extends('layouts.base-sidebar')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <!--begin::Card-->
    <div class="card">
        <!--begin::Card Header-->
        <div class="card-header">
            <div class="card-title">Documentation</div>
            <div class="card-tools">
                <form method="get" class="row row-cols-lg-auto g-3 align-items-center">
                    <div class="col-3">
                        <div class="input-group">
                            <input type="text" class="form-control" name="filter"
                                placeholder="Search image or tags..." aria-label="Search image or tags..."
                                value="{{ request()->filter }}">
                            <button class="btn btn-outline-secondary" type="submit">SEARCH</button>
                        </div>
                    </div>
                    <div class="col-3"><a href="{{ route('gallery.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-upload me-1"></i> UPLOAD</a></div>
                </form>
            </div>
        </div>
        <!--end::Card Header-->
        <!--begin::Card Body-->
        <div class="card-body">
            <!--begin::Gallery Grid-->
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xxl-4 g-3" id="gallery-grid">
                @foreach ($data as $item)
                    <div class="col" data-gallery-item="{{ implode(',', $item->tags) }}">
                        <figure class="card h-100 mb-0" title="{{ $item->title }} - {{ $item->short_description }}">
                            <div class="ratio ratio-4x3">
                                <img src="{{ $item->path_image }}"
                                    alt="{{ $item->title }} - {{ $item->short_description }}"
                                    class="card-img-top object-fit-cover" loading="lazy" />
                            </div>
                            <figcaption class="card-body d-flex align-items-start gap-2 py-2">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="fw-semibold mb-0 text-truncate">{{ $item->title }}</p>
                                    @if ($item->event)
                                        <p class="mb-1"><span class="badge text-bg-info"><i class="bi bi-calendar-event me-1"></i>{{ $item->event->post?->title }}</span></p>
                                    @endif
                                    <p class="fs-7 text-secondary mb-0">
                                        <i class="bi bi-calendar3 me-1"></i>{{ $item->taken_date }} · {{ $item->filePrettySize() }} ·
                                        @foreach ($item->tags ?? [] as $tag)
                                            <small class="badge badge-small text-bg-primary">{{ $tag }}</small>
                                        @endforeach
                                    </p>
                                </div>
                                <div class="dropdown flex-shrink-0">
                                    <button class="btn btn-tool" type="button" data-bs-toggle="dropdown"
                                        aria-expanded="false" aria-label="Actions for Old town at dusk">
                                        <i class="bi bi-three-dots-vertical" aria-hidden="true"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ $item->path_image }}">Download</a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider" />
                                        </li>
                                        <li>
                                            <form name="formdelete" id="formdelete" method="post" action="{{ route('gallery.destroy', $item->id) }}">
                                                @method('DELETE')
                                                <input class="dropdown-item text-danger" value="Delete" type="submit" name="delete"/>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </figcaption>
                        </figure>
                    </div>
                @endforeach
            </div>
            <!--end::Gallery Grid-->
            <!--begin::Empty State-->
            <p class="text-secondary text-center my-5" id="gallery-empty" role="status" hidden>
                Nothing in this category yet.
            </p>
            <!--end::Empty State-->
        </div>
        <!--end::Card Body-->
        <!--begin::Card Footer-->
        <div class="card-footer">
            {{ $data->links() }}
        </div>
        <!--end::Card Footer-->
    </div>
    <!--end::Card-->
@endsection
