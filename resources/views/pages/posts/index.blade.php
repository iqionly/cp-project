@extends('layouts.base-sidebar')

@section('content')
    <!--begin::Row-->
    <div class="row">

        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">List Posts</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Featured Images</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Images</th>
                                <th>Reviewed</th>
                                <th>Published</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $post)
                                <tr class="align-middle">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <img src="{{ $post->path_featured_image }}" class="img-thumbnail" alt="{{ $post->path_featured_image }}">
                                    </td>
                                    <td>{{ $post->title }}</td>
                                    <td>{{ $post->short_description }}</td>
                                    <td>...</td>
                                    <td>
                                        @if ($post->is_reviewed)
                                            <span class="badge text-bg-success">reviewed</span>
                                        @else
                                            <span class="badge text-bg-warning">not reviewed</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($post->is_published)
                                            <span class="badge text-bg-success">published</span>
                                        @else
                                            <span class="badge text-bg-warning">not publish</span>
                                        @endif
                                    </td>
                                    <td>{{ $post->created_at }}</td>
                                    <td>{{ $post->updated_at }}</td>
                                    <td>
                                        <form action="{{ route('master-data.member.delete', [$post->id]) }}"
                                            method="post">
                                            @method('DELETE')
                                            @csrf
                                            <input type="submit" class="btn btn-danger btn-sm m-1" value="DELETE" />
                                        </form>
                                        <a href="{{ route('master-data.member.edit', [$post->id]) }}"
                                            class="btn btn-info btn-sm m-1">EDIT</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="text-center">
                        <div class="btn-group m-2" role="group" aria-label="pagination">
                            @for ($i = 1; $i <= min(10, $data->lastPage()); $i++)
                                @if ($data->currentPage() > 1 && $i == 1)
                                    <a href="?page={{ $data->currentPage() - 1 }}" class="btn btn-secondary">Prev</a>
                                @endif
                                <a href="?page={{ $i }}"
                                    class="btn btn-secondary {{ $data->currentPage() == $i ? 'active' : null }}">{{ $i }}</a>
                                @if ($data->currentPage() < 10 && $i == 10)
                                    <a href="?page={{ $data->currentPage() + 1 }}" class="btn btn-secondary">Next</a>
                                @endif
                            @endfor
                        </div>
                    </div>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!--end::Row-->
@endsection
