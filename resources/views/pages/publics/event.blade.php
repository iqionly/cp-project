@extends('layouts.base-topbar')

@section('content')
    <div class="container-fluid">
        @if ($currentEvent)
            <div class="row g-4">
                <!--begin::Main Event Column-->
                <div class="col-lg-8">
                    <div class="card card-outline card-primary shadow-sm mb-4">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h3 class="card-title fw-bold mb-0">
                                {{ $currentEvent->post->title }}
                            </h3>
                        </div>
                        <!-- /.card-header -->

                        <div class="card-body">
                            <!-- Featured Image Banner -->
                            <div class="mb-4">
                                <img src="{{ $currentEvent->post->path_featured_image }}"
                                    class="img-fluid rounded w-100 object-fit-cover border shadow-sm"
                                    style="max-height: 440px;" alt="{{ $currentEvent->post->title }}">
                            </div>

                            <!-- Content / Description -->
                            <div class="event-contents fs-6 lh-lg mb-4 text-body">
                                {!! $currentEvent->post->contents !!}
                            </div>

                            <!-- Event Gallery / Dokumentasi Kegiatan Terkait -->
                            @if ($currentEvent->galleries->isNotEmpty() || (!empty($currentEvent->post->path_images) && count($currentEvent->post->path_images) > 0))
                                <div class="border-top pt-3 mt-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="fw-bold mb-0">
                                            <i class="bi bi-images me-2 text-primary"></i>Dokumentasi Kegiatan
                                        </h5>
                                        <small class="text-body-secondary">Arahkan kursor pada gambar untuk info</small>
                                    </div>
                                    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 g-3">
                                        @foreach ($currentEvent->galleries as $galleryItem)
                                            @php
                                                $hoverTooltip = trim($currentEvent->post->title . ($galleryItem->description ? ' - ' . $galleryItem->description : ($galleryItem->title ? ' - ' . $galleryItem->title : '')));
                                            @endphp
                                            <div class="col">
                                                <div class="card h-100 shadow-sm border-0 overflow-hidden" title="{{ $hoverTooltip }}">
                                                    <a href="{{ $galleryItem->path_image }}" target="_blank" class="d-block ratio ratio-4x3 overflow-hidden" title="{{ $hoverTooltip }}">
                                                        <img src="{{ $galleryItem->path_image }}"
                                                             class="card-img-top object-fit-cover w-100 h-100"
                                                             alt="{{ $hoverTooltip }}"
                                                             loading="lazy"
                                                             style="transition: transform .3s ease;"
                                                             onmouseover="this.style.transform='scale(1.05)'"
                                                             onmouseout="this.style.transform='scale(1)'"
                                                             title="{{ $hoverTooltip }}">
                                                    </a>
                                                    <div class="card-footer text-center py-2 px-1 border-0">
                                                        <small class="text-body-secondary fw-medium">
                                                            <i class="bi bi-calendar3 me-1"></i>{{ $galleryItem->taken_date }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                        {{-- Gambar tambahan dari post bila ada --}}
                                        @foreach ($currentEvent->post->path_images ?? [] as $image)
                                            @php
                                                $postHoverTooltip = trim($currentEvent->post->title . ($currentEvent->post->short_description ? ' - ' . $currentEvent->post->short_description : ''));
                                            @endphp
                                            <div class="col">
                                                <div class="card h-100 shadow-sm border-0 overflow-hidden" title="{{ $postHoverTooltip }}">
                                                    <a href="{{ url('storage/' . $image) }}" target="_blank" class="d-block ratio ratio-4x3 overflow-hidden" title="{{ $postHoverTooltip }}">
                                                        <img src="{{ url('storage/' . $image) }}"
                                                             class="card-img-top object-fit-cover w-100 h-100"
                                                             alt="{{ $postHoverTooltip }}"
                                                             loading="lazy"
                                                             style="transition: transform .3s ease;"
                                                             onmouseover="this.style.transform='scale(1.05)'"
                                                             onmouseout="this.style.transform='scale(1)'"
                                                             title="{{ $postHoverTooltip }}">
                                                    </a>
                                                    <div class="card-footer bg-white text-center py-2 px-1 border-0">
                                                        <small class="text-body-secondary fw-medium">
                                                            <i class="bi bi-calendar3 me-1"></i>{{ $currentEvent->created_at?->format('d M Y') }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                        <!-- /.card-body -->

                        <div
                            class="card-footer text-body-secondary d-flex flex-wrap justify-content-between align-items-center gap-2 small">
                            <span>
                                <i class="bi bi-person me-1"></i> Organized by:
                                <strong>{{ ucwords($currentEvent->post->user?->name ?? 'Admin') }}</strong>
                            </span>
                            <span>
                                <i class="bi bi-clock-history me-1"></i> Published: {{ $currentEvent->created_at }}
                            </span>
                        </div>
                        <!-- /.card-footer -->
                    </div>
                    <!-- /.card -->
                </div>
                <!--end::Main Event Column-->

                <!--begin::Sidebar Column (Event Info & Next/Prev Navigation)-->
                <div class="col-lg-4">
                    <!-- Event Information Card with AdminLTE Info Boxes -->
                    <div class="card card-outline card-info shadow-sm mb-4">
                        <div class="card-header">
                            <h3 class="card-title fw-bold">
                                <i class="bi bi-info-circle-fill me-2 text-info"></i>Event Information
                            </h3>
                        </div>
                        <div class="card-body p-3">
                            <!-- Date & Time Info Box -->
                            <div class="info-box mb-3 shadow-none border">
                                <span class="info-box-icon text-bg-primary shadow-sm">
                                    <i class="bi bi-calendar-event"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text text-body-secondary">Event Period</span>
                                    <span class="info-box-number fs-6">{{ $currentEvent->short_event_period }}</span>
                                </div>
                            </div>

                            <!-- Location Info Box -->
                            <div class="info-box mb-3 shadow-none border">
                                <span class="info-box-icon text-bg-success shadow-sm">
                                    <i class="bi bi-geo-alt"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text text-body-secondary">Location</span>
                                    <span class="info-box-number fs-6 text-truncate" title="{{ $currentEvent->location }}">
                                        {{ $currentEvent->location }}
                                    </span>
                                </div>
                            </div>

                            <!-- Quota Info Box -->
                            <div class="info-box mb-0 shadow-none border">
                                <span class="info-box-icon text-bg-warning shadow-sm">
                                    <i class="bi bi-people"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text text-body-secondary">Participants Quota</span>
                                    <span class="info-box-number fs-6">
                                        {{ $currentEvent->min_attendant }} &ndash; {{ $currentEvent->max_attendant }}
                                        Persons
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Next Event Card (WITH FEATURED IMAGE) -->
                    @if ($nextEvent)
                        <div class="card card-outline card-primary shadow-sm mb-4">
                            <div class="card-header bg-primary text-white">
                                <h3 class="card-title fw-bold text-white mb-0">
                                    <i class="bi bi-arrow-right-circle-fill me-2"></i>Next Event
                                </h3>
                            </div>
                            <div class="card-body p-3">
                                <a href="{{ route('public.events.show', $nextEvent) }}" class="text-decoration-none">
                                    <img src="{{ $nextEvent->post->path_featured_image }}"
                                        alt="{{ $nextEvent->post->title }}"
                                        class="img-fluid rounded mb-3 w-100 object-fit-cover shadow-sm border"
                                        style="height: 180px;">
                                </a>

                                <h5 class="fw-bold mb-2">
                                    <a href="{{ route('public.events.show', $nextEvent) }}"
                                        class="text-decoration-none text-body" title="{{ $nextEvent->post->title }}">
                                        {{ $nextEvent->post->title }}
                                    </a>
                                </h5>

                                <p class="text-body-secondary small mb-1">
                                    <i class="bi bi-calendar3 me-1 text-primary"></i>{{ $nextEvent->short_event_period }}
                                </p>
                                <p class="text-body-secondary small mb-3 text-truncate" title="{{ $nextEvent->location }}">
                                    <i class="bi bi-geo-alt me-1 text-success"></i>{{ $nextEvent->location }}
                                </p>

                                <a href="{{ route('public.events.show', $nextEvent) }}"
                                    class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                                    <span>Go to Next Event</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Previous Event Card -->
                    @if ($prevEvent)
                        <div class="card card-outline card-secondary shadow-sm mb-4">
                            <div class="card-header">
                                <h3 class="card-title fw-bold text-body mb-0">
                                    <i class="bi bi-arrow-left-circle-fill me-2 text-secondary"></i>Previous Event
                                </h3>
                            </div>
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center gap-3">
                                    <a href="{{ route('public.events.show', $prevEvent) }}" class="flex-shrink-0">
                                        <img src="{{ $prevEvent->post->path_featured_image }}"
                                            alt="{{ $prevEvent->post->title }}"
                                            class="rounded object-fit-cover border shadow-sm"
                                            style="width: 80px; height: 60px;">
                                    </a>
                                    <div class="overflow-hidden flex-grow-1">
                                        <h6 class="mb-1 text-truncate fw-bold">
                                            <a href="{{ route('public.events.show', $prevEvent) }}"
                                                class="text-decoration-none text-body"
                                                title="{{ $prevEvent->post->title }}">
                                                {{ $prevEvent->post->title }}
                                            </a>
                                        </h6>
                                        <small class="text-body-secondary d-block text-truncate">
                                            <i class="bi bi-calendar3 me-1"></i>{{ $prevEvent->short_event_period }}
                                        </small>
                                    </div>
                                </div>

                                <a href="{{ route('public.events.show', $prevEvent) }}"
                                    class="btn btn-outline-secondary btn-sm w-100 mt-3 d-flex align-items-center justify-content-center gap-1">
                                    <i class="bi bi-arrow-left"></i>
                                    <span>View Previous Event</span>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
                <!--end::Sidebar Column-->

                <!--begin::All Events Schedule Table-->
                @if ($allEvents->count() > 1)
                    <div class="col-12">
                        <div class="card card-outline card-dark shadow-sm">
                            <div class="card-header">
                                <h3 class="card-title fw-bold mb-0">
                                    <i class="bi bi-calendar-week-fill me-2 text-primary"></i>Active & Upcoming Events
                                </h3>
                                <div class="card-tools">
                                    <div class=" row row-cols-lg-auto g-3 align-items-center">
                                        <div class="col-3">
                                            <span class="badge text-bg-secondary me-3">{{ $allEvents->count() }}
                                                Events</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-header -->

                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th style="width: 100px;">Preview</th>
                                                <th>Event Title</th>
                                                <th>Event Period</th>
                                                <th>Location</th>
                                                <th>Quota</th>
                                                <th style="width: 150px;" class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($allEvents as $item)
                                                @if ($loop->iteration >= 6)
                                                    @break
                                                @endif
                                                <tr class="{{ $item->id === $currentEvent->id ? 'table-primary' : '' }}">
                                                    <td>
                                                        <img src="{{ $item->post->path_featured_image }}"
                                                            class="rounded border shadow-sm object-fit-cover"
                                                            style="width: 80px; height: 50px;"
                                                            alt="{{ $item->post->title }}">
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="fw-bold d-block text-body">{{ $item->post->title }}</span>
                                                        <small class="text-body-secondary d-block text-truncate"
                                                            style="max-width: 500px;">
                                                            {{ $item->post->medium_description }}
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <small class="text-nowrap">
                                                            <i
                                                                class="bi bi-calendar3 me-1 text-primary"></i>{{ $item->short_event_period }}
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <small class="text-nowrap">
                                                            <i
                                                                class="bi bi-geo-alt me-1 text-success"></i>{{ $item->location }}
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <small class="text-nowrap">
                                                            <i
                                                                class="bi bi-people me-1 text-warning"></i>{{ $item->min_attendant }}
                                                            - {{ $item->max_attendant }}
                                                        </small>
                                                    </td>
                                                    <td class="text-end">
                                                        @if ($item->id === $currentEvent->id)
                                                            <span class="badge text-bg-primary py-2 px-3">Viewing
                                                                Now</span>
                                                        @else
                                                            <a href="{{ route('public.events.show', $item) }}"
                                                                class="btn btn-outline-primary btn-sm">
                                                                SEE DETAILS
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            @if (count($allEvents) > 5)
                                <div class="card-footer justify-content-between">
                                    <a href="#" class="col-auto btn btn-outline-secondary">SEE HISTORY</a>
                                    <a href="#" class="col-auto btn btn-primary float-end">SEE MORE</a>
                                </div>
                            @endif
                        </div>
                        <!-- /.card -->
                    </div>
                @endif
                <!--end::All Events Schedule Table-->
            </div>
        @else
            <div class="card card-outline card-secondary shadow-sm text-center p-5 my-4">
                <div class="card-body">
                    <i class="bi bi-calendar-x text-body-secondary fs-1 d-block mb-3"></i>
                    <h3 class="fw-bold">No Events Available</h3>
                    <p class="text-body-secondary">There are currently no active or upcoming events scheduled. Please check
                        back later!</p>
                </div>
            </div>
        @endif
    </div>
@endsection
