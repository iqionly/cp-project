@extends('layouts.base-sidebar')

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card mb-4 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title fw-bold mb-0">Kelola Merchandise Club</h3>
                    <div class="card-tools">
                        <a href="{{ route('merchandise.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i> TAMBAH MERCHANDISE
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 10px">#</th>
                                <th style="width: 100px">Foto</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th style="width: 150px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if ($item->path_image)
                                            <img src="{{ $item->path_image }}" class="img-thumbnail" style="width: 70px; height: 70px; object-fit: cover;" alt="{{ $item->name }}">
                                        @else
                                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded" style="width: 70px; height: 70px;">
                                                <i class="bi bi-bag"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $item->name }}</div>
                                        <small class="text-body-secondary">{{ Str::limit($item->description, 60) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge text-bg-info">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $item->stock > 0 ? 'text-bg-success' : 'text-bg-danger' }}">
                                            {{ $item->stock }} unit
                                        </span>
                                    </td>
                                    <td>
                                        @if ($item->is_available)
                                            <span class="badge text-bg-success">Tersedia</span>
                                        @else
                                            <span class="badge text-bg-secondary">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->created_at?->format('d M Y') ?? '-' }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('merchandise.edit', $item->id) }}" class="btn btn-info btn-sm">
                                                <i class="bi bi-pencil-square"></i> EDIT
                                            </a>
                                            <form action="{{ route('merchandise.delete', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus merchandise ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-body-secondary">
                                        Belum ada data merchandise.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    {{ $data->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
