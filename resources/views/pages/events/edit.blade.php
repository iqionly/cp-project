@extends('layouts.base-sidebar')

@section('content')
    <!--begin::Row-->
    <div class="row">
        <div class="col-md-12">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Edit Event</h3>
                </div>
                <!-- /.card-header -->
                <form method="post" enctype="multipart/form-data">
                    @method($method)
                    @csrf
                    <div class="card-body">
                        <div class="row">

                            <div class="col-6 mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" aria-describedby="title" name="post[title]" value="{{ $old ? $old->post->title : $data->post->title }}" />
                            </div>

                            <div class="col-6 mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control" id="location" aria-describedby="location" name="location" value="{{ $old ? $old->location : $data->location }}" />
                            </div>

                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" aria-describedby="description" name="post[description]" rows="7">{{ $old ? $old->post->description : $data->post->description }}</textarea>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label for="start-at" class="form-label">Event Period</label>
                                <input
                                    type="text"
                                    id="event-period"
                                    name="event_period"
                                    class="form-control"
                                    placeholder="Select a reporting period"
                                    value="{{ $old ? $old->event_period : $data->event_period }}"
                                />
                                <div class="form-text">Pick a start date, then an end date.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="max-attendant" class="form-label">Max Attendant</label>
                                <input type="number" id="max-attendant" name="max_attendant" class="form-control" placeholder="Input max attendant"  value="{{ $old ? $old->max_attendant : ($data->max_attendant ?? 0) }}"/>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-3 mb-3">
                                <img src="{{ $data->post->path_featured_image }}" alt="{{ $data->post->path_featured_image }}" class="img-thumbnail w-100 mb-3"/>
                                <label for="image" class="form-label">Featured Image</label>
                                <input type="file" class="form-control" id="image" aria-describedby="image"
                                    name="post[path_featured_image]" value="{{ $old ? $old->path_featured_image : $data->post->path_featured_image }}" />
                            </div>

                            <div class="col mb-3 row">
                                @for($i = 1; $i < 4; $i++)
                                <div class="col-4 form-group">
                                    <img src="{{ isset($data->post->path_images[$i]) ? url('storage/' . $data->post->path_images[$i]) : "https://placehold.co/200x100.png" }}" alt="{{ $data->post->path_images[$i] ?? null }}" class="img-thumbnail w-100 mb-3"/>
                                    <label for="image" class="form-label">Image {{ $i }}</label>
                                    <input type="file" class="form-control" aria-describedby="image" name="post[images][{{ $i }}]" />
                                </div>
                                @endfor
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-12 mb-3" style="height:fit-content;">
                                <label for="image" class="form-label">Content Post</label>
                                <input type="hidden" name="post[contents]" id="contents" value="{{ $old ? $old->post->contents : $data->post->contents }}"/>
                                <div id="editor" style="height:500px;"></div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" name="post[reviewed]" id="reviewed" {{ $data->post->is_reviewed ? 'checked' : null }}>
                                    <label class="form-check-label" for="reviewed">
                                        Review?
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" name="post[published]" id="published" {{ $data->post->is_published ? 'checked' : null }}>
                                    <label class="form-check-label" for="published">
                                        Publish?
                                    </label>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>

                </form>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!--end::Row-->
@endsection

@push('scripts')
<script>
    (function() {
        const quill = new Quill('#editor', {
            theme: 'snow'
        });

        if(document.getElementById('contents').value) {
            const content = JSON.parse(document.getElementById('contents').value);
            quill.setContents(content);
        }

        quill.on('text-change', (delta, oldDelta, source) => {
            document.getElementById('contents').value = JSON.stringify(quill.getContents());
        });

        flatpickr('#event-period', {
            mode: 'range',
            enableTime: true,
            dateFormat: 'Y-m-d H:i',
            time_24hr: true,
            allowInput: true,
        });

    })();
</script>
@endpush
