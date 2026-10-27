@extends('layouts.base-sidebar')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card card-outline card-primary mb-4 shadow-sm">
                <div class="card-header">
                    <h3 class="card-title fw-bold">
                        {{ $method === 'post' ? 'Tambah Merchandise Baru' : 'Edit Merchandise' }}
                    </h3>
                </div>
                <!-- /.card-header -->
                <form method="POST" enctype="multipart/form-data">
                    @method($method)
                    @csrf
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $data->name) }}" required />
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                                <input type="number" step="100" min="0" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $data->price) }}" required />
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="stock" class="form-label">Stok <span class="text-danger">*</span></label>
                                <input type="number" min="0" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $data->stock ?? 0) }}" required />
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $data->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="path_image" class="form-label">Foto Merchandise</label>
                            @if ($data->path_image)
                                <div class="mb-2">
                                    <img src="{{ $data->path_image }}" alt="{{ $data->name }}" class="img-thumbnail" style="max-height: 150px;">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('path_image') is-invalid @enderror" id="path_image" name="path_image" accept="image/*" />
                            <div class="form-text">Format: JPG, PNG, WEBP. Maksimal 2MB.</div>
                            @error('path_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_available" name="is_available" value="1" {{ old('is_available', $data->is_available ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_available">Tampilkan di Halaman Publik (Tersedia)</label>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('merchandise.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
