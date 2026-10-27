@extends('layouts.base-topbar')

@php
    static $contentClass = 'container';
@endphp

@section('content')
    @if($post->getRawOriginal('path_featured_image'))
        <img src="{{ url($post->path_featured_image) }}" class="img-thumbnail w-100 mb-5" alt="{{ $post->path_featured_image }}"/>
    @endif
    <h1 class="mb-2">{{ $post->title }}</h1>
    <div class="d-flex text-muted mb-3">
        <span class="me-2">
            <i class="bi bi-person"></i> Author: {{ ucwords($author->name) }}
        </span>
        <span class="me-2">
            <i class="bi bi-clock"></i> Created At: {{ $post->created_at }}
        </span>
        <span class="me-2">
            <i class="bi bi-clock-history"></i> Updated At: {{ $post->updated_at }}
        </span>
    </div>
    <div class="post-contents">
        {!! $post->contents !!}
    </div>

    <div class="row justify-content-md-center mb-5">
        @foreach($post->path_images ?? [] as $img)
        <div class="col-4"><img src="{{ url('storage/' . $img) }}" class="img-thumbnail" style="height: 400px;"/></div>
        @endforeach
    </div>
@endsection

@push('scripts')
<script>
    (function() {
        const quill = new Quill('.post-contents', {
            readOnly: true,
            modules: {
                syntax: true,
                toolbar: null,
            },
            theme: 'bubble',
        });

    })();
</script>
@endpush
