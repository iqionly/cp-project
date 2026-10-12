@extends('layouts.base-sidebar')

@section('content')
    <!--begin::Row-->
    <div class="row">

        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">List Posts</h3>
                    <div class="card-tools">
                        <a href="{{ route('event.create') }}" class="btn btn-primary btn-sm">CREATE</a>
                    </div>
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
                                <th>Location</th>
                                <th>Event Time</th>
                                <th>Min/Max Attendant</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $event)
                                <tr class="align-middle">
                                    <td>{{ $loop->iteration }}</td>
                                    <td style="max-width: 200px;">
                                        <img src="{{ $event->post->path_featured_image }}" class="img-thumbnail w-100" alt="{{ $event->post->path_featured_image }}">
                                    </td>
                                    <td>{{ $event->post->title }}</td>
                                    <td>{{ $event->post->short_description }}</td>
                                    <td>{{ $event->location }}</td>
                                    <td>{{ $event->short_event_period }}</td>
                                    <td>{{ $event->min_attendant }} / {{ $event->max_attendant }} orang</td>
                                    <td>{{ $event->created_at }}</td>
                                    <td>{{ $event->updated_at }}</td>
                                    <td>
                                        <form action="{{ route('event.delete', [$event->id]) }}"
                                            method="post">
                                            @method('DELETE')
                                            @csrf
                                            <input type="submit" class="btn btn-danger btn-sm m-1" value="DELETE" />
                                        </form>
                                        <a href="{{ route('event.edit', [$event->id]) }}"
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
