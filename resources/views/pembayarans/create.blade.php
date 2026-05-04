@extends('layouts.app')
@section('title', 'Bayar Hutang')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">

        <div class="card shadow-sm border-0 mb-3 border-start border-4 border-danger">
            <div class="card-body">
                <h6 class="fw-bold text-danger mb-3"><i class="bi bi-credit-card me-1"></i>Info Hutang</h6>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="text-muted small">Pelanggan</div>
                        <div class="fw-semibold">{{ $hutang->transaksi->customer->nama }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">Jatuh Tempo</div>
                        <div class="fw-semibold">{{ \Carbon\Carbon::parse($hutang->jatuh_tempo)->format('d/m/Y') }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">Total Hutang</div>
                        <div>Rp {{ number_format($hutang->total_hutang, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">Sisa Hutang</div>
                        <div class="fw-bold text-danger fs-5">Rp {{ number_format($hutang->sisa_hutang, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold"><i class="bi bi-cash me-2 text-success"></i>Form Pembayaran</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('pembayarans.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="hutang_id" value="{{ $hutang->id }}">

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Jumlah Bayar <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text fw-semibold">Rp</span>
                            <input type="number" name="jumlah_bayar" id="jumlahBayar"
                                   class="form-control form-control-lg @error('jumlah_bayar') is-invalid @enderror"
                                   value="{{ old('jumlah_bayar') }}"
                                   min="1" max="{{ $hutang->sisa_hutang }}" placeholder="0">
                            @error('jumlah_bayar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-text">Maksimal: Rp {{ number_format($hutang->sisa_hutang, 0, ',', '.') }}</div>
                    </div>

                    <div class="mb-4">
                        <button type="button" id="btnLunas" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-check-all me-1"></i>
                            Bayar Lunas (Rp {{ number_format($hutang->sisa_hutang, 0, ',', '.') }})
                        </button>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i>Simpan Pembayaran</button>
                        <a href="{{ route('hutangs.show', $hutang) }}" class="btn btn-light"><i class="bi bi-arrow-left me-1"></i>Batal</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('btnLunas').addEventListener('click', function () {
        document.getElementById('jumlahBayar').value = {{ $hutang->sisa_hutang }};
    });
</script>
@endpush
