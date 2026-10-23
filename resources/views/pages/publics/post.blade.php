@extends('layouts.base-topbar')

@section('content')
    <div class="container">
        <img src="{{ $post->path_featured_image }}" class="img-thumbnail w-100 mb-5" alt="{{ $post->path_featured_image }}">
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
        {!! $post->contents !!}

        <div class="row justify-content-md-center mb-5">
            @foreach($post->path_images ?? [] as $img)
            <div class="col-4"><img src="{{ url('storage/' . $img) }}" class="img-thumbnail" style="height: 400px;"/></div>
            @endforeach
        </div>
    </div>
@endsection
