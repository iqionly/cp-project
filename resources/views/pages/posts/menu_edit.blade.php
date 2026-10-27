@extends('layouts.base-sidebar')

@section('content')
    <!--begin::Row-->
    <div class="row">

        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Edit Menu</h3>
                </div>
                <!-- /.card-header -->
                <form method="post" enctype="multipart/form-data">
                    @method($method)
                    @csrf
                    <div class="card-body">
                        <div class="row">

                            @if(isset($selectedPost))
                            <div class="col-12 mb-3">
                                <label for="post" class="form-label">Post</label>
                                <select name="post_id" id="post_id" class="form-control disabled" required disabled>
                                    <option value="{{ $selectedPost->id }}" selected> {{ $selectedPost->title }} - By: {{ $selectedPost->user->name }}
                                    </option>
                                </select>
                            </div>
                            @endif

                            <div class="col-3 mb-3">
                                <label for="order" class="form-label">Order Menu</label>
                                <input type="number" min="0" class="form-control" id="order"
                                    aria-describedby="order" name="order"
                                    value="{{ old('default') ?? $data->default }}" />
                            </div>

                            <div class="col-6 mb-3">
                                <label for="menu_title" class="form-label">Menu Title</label>
                                <input type="text" class="form-control" id="menu_title" aria-describedby="menu_title"
                                    name="menu_title" value="{{ old('menu_title') ?? $data->menu_title }}" />
                            </div>

                            <div class="col-3 mb-3">
                                <label for="icon" class="form-label">Icons</label>
                                <div class="input-group"></div>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="icon" aria-describedby="icon"
                                        name="icon" value="{{ old('icons') ?? $data->icons }}" />
                                    <i class="input-group-text ba {{ old('icons') ?? $data->icons }}"></i>
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" class="form-control" id="slug" aria-describedby="slug"
                                    name="slug" value="{{ old('slug') ?? $data->slug }}" />
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

            if (document.getElementById('contents').value) {
                quill.root.innerHTML = document.getElementById('contents').value;
            }

            quill.on('text-change', (delta, oldDelta, source) => {
                if (quill.root.innerHTML.length == 0) {
                    return;
                }
                document.getElementById('contents').value = quill.root.innerHTML;
            });

        })();
    </script>
@endpush
