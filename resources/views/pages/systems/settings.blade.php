@extends('layouts.base-sidebar')

@section('content')
    <!--begin::Row-->
    <div class="row">

        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Settings Social Media</h3>
                </div>
                <!-- /.card-header -->
                <form method="post" enctype="multipart/form-data">
                    @method($method)
                    @csrf
                    <div class="card-body">
                        <div class="row">

                            <div class="col-6 mb-3">
                                <label for="facebook" class="form-label">Facebook</label>
                                <input type="text" class="form-control" id="facebook" aria-describedby="facebook" name="facebook" value="{{ old('facebook') }}" />
                            </div>

                            <div class="col-6 mb-3">
                                <label for="instagram" class="form-label">Instagram</label>
                                <input type="text" class="form-control" id="instagram" aria-describedby="Instagram" name="instagram" value="{{ old('Instagram') }}" />
                            </div>

                            <div class="col-6 mb-3">
                                <label for="tiktok" class="form-label">Tiktok</label>
                                <input type="text" class="form-control" id="Tiktok" aria-describedby="tiktok" name="Tiktok" value="{{ old('Tiktok') }}" />
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
