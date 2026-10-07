@extends('layouts.base-sidebar')

@section('content')
    <!--begin::Row-->
    <div class="row">

        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Edit Post</h3>
                </div>
                <!-- /.card-header -->
                <form method="post" enctype="multipart/form-data">
                    @method('put')
                    @csrf
                    <div class="card-body">
                        <div class="row">

                            <div class="col-12 mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" aria-describedby="title" name="title" value="{{ $old ? $old->title : $data->title }}" />
                            </div>

                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" aria-describedby="description" name="description" rows="7">{{ $old ? $old->description : $data->description }}</textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <img src="{{ $data->path_featured_image }}" alt="{{ $data->path_featured_image }}" class="img-thumbnail w-100 mb-3"/>
                                <label for="image" class="form-label">Featured Image</label>
                                <input type="file" class="form-control" id="image" aria-describedby="image"
                                    name="path_featured_image" value="{{ $old ? $old->path_featured_image : $data->path_featured_image }}" />
                            </div>

                            <div class="col mb-3 row">
                                @for($i = 1; $i < 4; $i++)
                                <div class="col-4 form-group">
                                    <img src="{{ $data->path_images[$i] ?? "https://placehold.co/200x100.png" }}" alt="{{ $data->path_images[$i] ?? null }}" class="img-thumbnail w-100 mb-3"/>
                                    <label for="image" class="form-label">Image {{ $i }}</label>
                                    <input type="file" class="form-control" aria-describedby="image" name="images[{{ $i }}]" />
                                </div>
                                @endfor
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-3" style="height:fit-content;">
                                <label for="image" class="form-label">Content Post</label>
                                <div id="editor" style="height:500px;"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" name="reviewed" id="reviewed">
                                    <label class="form-check-label" for="reviewed">
                                        Review?
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" name="published" id="published">
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
    })();
</script>
@endpush
