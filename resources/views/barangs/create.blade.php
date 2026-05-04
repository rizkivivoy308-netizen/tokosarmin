@extends('layouts.app')
@section('title', 'Tambah Barang')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold"><i class="bi bi-plus-square me-2 text-success"></i>Tambah Barang</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('barangs.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" name="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror"
                               value="{{ old('nama_barang') }}" placeholder="Contoh: Beras 5kg">
                        @error('nama_barang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Harga <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror"
                                   value="{{ old('harga') }}" min="0" placeholder="0">
                            @error('harga')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Stok <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror"
                                   value="{{ old('stok', 0) }}" min="0">
                            <span class="input-group-text">pcs</span>
                            @error('stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i>Simpan</button>
                        <a href="{{ route('barangs.index') }}" class="btn btn-light"><i class="bi bi-arrow-left me-1"></i>Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
