@extends('layouts.app')
@section('title', 'Edit Pelanggan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Pelanggan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('customers.update', $customer) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Pelanggan <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                               value="{{ old('nama', $customer->nama) }}">
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $customer->alamat) }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">No. WhatsApp <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-whatsapp text-success"></i></span>
                            <input type="text" name="no_wa" class="form-control @error('no_wa') is-invalid @enderror"
                                   value="{{ old('no_wa', $customer->no_wa) }}">
                            @error('no_wa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning"><i class="bi bi-save me-1"></i>Update</button>
                        <a href="{{ route('customers.index') }}" class="btn btn-light"><i class="bi bi-arrow-left me-1"></i>Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
