@extends('layouts.base-sidebar')

@section('content')
    <!--begin::Row-->
    <div class="row">

        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">List Public Menu</h3>
                    <div class="card-tools">
                        <a href="{{ route('menu-setting.create') }}" class="btn btn-primary btn-sm">CREATE</a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10px">#Order</th>
                                <th>Title</th>
                                <th>Icons</th>
                                <th>Path Slug</th>
                                <th>Page Featured Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $menu)
                                <tr class="align-middle">
                                    <td>{{ $menu->default }}</td>
                                    <td>{{ $menu->menu_title }}</td>
                                    <td><i class="ba {{ $menu->icons }}"></i></td>
                                    <td>
                                        <span class="badge text-bg-info">{{ $menu->slug }}</span>
                                    </td>
                                    <td>
                                        <img src="{{ $menu->post->path_featured_image }}" class="img-thumbnail" style="max-width: 150px;" alt="{{ $menu->post->path_featured_image }}">
                                    </td>
                                    <td>
                                        <form action="{{ route('menu-setting.delete', [$menu->post_id]) }}"
                                            method="post">
                                            @method('DELETE')
                                            @csrf
                                            <input type="submit" class="btn btn-danger btn-sm m-1" value="DELETE" />
                                        </form>
                                        <a href="{{ route('menu-setting.edit', [$menu->post_id]) }}"
                                            class="btn btn-info btn-sm m-1">EDIT</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $data->links() }}
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!--end::Row-->
@endsection
