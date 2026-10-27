@extends('layouts.base-sidebar')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card card-outline card-primary mb-4 shadow-sm">
                <div class="card-header">
                    <h3 class="card-title fw-bold">Unggah Foto Dokumentasi Kegiatan</h3>
                </div>
                <!-- /.card-header -->
                <form action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="event_id" class="form-label">Kegiatan / Event Terkait (Opsional)</label>
                            <select class="form-select @error('event_id') is-invalid @enderror" id="event_id" name="event_id">
                                <option value="">-- Pilih Kegiatan Terkait (Jika ada) --</option>
                                @foreach ($events as $event)
                                    <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                        {{ $event->post?->title ?? 'Kegiatan #'.$event->id }} ({{ $event->short_event_period }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Pilih event jika dokumentasi ini berhubungan dengan kegiatan club tertentu.</div>
                            @error('event_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Foto / Momen <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" placeholder="Contoh: Titik Kumpul Touring Merdeka" required />
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="taken_at" class="form-label">Tanggal Foto Diambil (Take Date)</label>
                            <input type="date" class="form-control @error('taken_at') is-invalid @enderror" id="taken_at" name="taken_at" value="{{ old('taken_at', date('Y-m-d')) }}" />
                            <div class="form-text">Tanggal saat foto ini diabadikan.</div>
                            @error('taken_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi / Keterangan</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Keterangan singkat momen foto kegiatan...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="path_image" class="form-label">File Foto <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('path_image') is-invalid @enderror" id="path_image" name="path_image" accept="image/*" required />
                            <div class="form-text">Maksimal 5MB (Format: JPG, JPEG, PNG, WEBP).</div>
                            @error('path_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tags" class="form-label">Tags</label>
                            <input type="text" class="form-control @error('tags') is-invalid @enderror" id="tags" name="tags" value="{{ old('tags') }}" placeholder="touring, gathering, baksos (pisahkan dengan koma)" />
                            <div class="form-text">Pisahkan antar tag dengan tanda koma (,).</div>
                            @error('tags')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('gallery.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload me-1"></i> Unggah Foto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
