@extends('layouts.app')
@section('title', 'Transaksi Baru')

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold"><i class="bi bi-cart-plus me-2 text-primary"></i>Form Transaksi Baru</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('transaksis.store') }}" method="POST" id="formTransaksi">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pelanggan <span class="text-danger">*</span></label>
                        <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror">
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->nama }} ({{ $customer->no_wa }})
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Metode Pembayaran <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metode_pembayaran" id="cash" value="cash"
                                       {{ old('metode_pembayaran', 'cash') === 'cash' ? 'checked' : '' }}>
                                <label class="form-check-label" for="cash">
                                    <i class="bi bi-cash text-success me-1"></i>Cash
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metode_pembayaran" id="hutang" value="hutang"
                                       {{ old('metode_pembayaran') === 'hutang' ? 'checked' : '' }}>
                                <label class="form-check-label" for="hutang">
                                    <i class="bi bi-credit-card text-warning me-1"></i>Hutang
                                </label>
                            </div>
                        </div>
                        @error('metode_pembayaran')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Barang <span class="text-danger">*</span></label>
                        <div id="barangContainer">
                            <div class="row g-2 mb-2 barang-row align-items-end">
                                <div class="col-md-5">
                                    <label class="form-label small text-muted">Nama Barang</label>
                                    <select name="barang_id[]" class="form-select form-select-sm barang-select">
                                        <option value="">-- Pilih Barang --</option>
                                        @foreach($barangs as $barang)
                                            <option value="{{ $barang->id }}" data-harga="{{ $barang->harga }}" data-stok="{{ $barang->stok }}">
                                                {{ $barang->nama_barang }} (Rp {{ number_format($barang->harga, 0, ',', '.') }}) — Stok: {{ $barang->stok }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small text-muted">Harga</label>
                                    <input type="text" class="form-control form-control-sm harga-display" placeholder="Rp 0" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small text-muted">Jumlah</label>
                                    <input type="number" name="jumlah[]" class="form-control form-control-sm jumlah-input" value="1" min="1">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small text-muted">Subtotal</label>
                                    <input type="text" class="form-control form-control-sm subtotal-display" placeholder="Rp 0" readonly>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-baris" title="Hapus">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="btnTambahBarang" class="btn btn-sm btn-outline-success mt-1">
                            <i class="bi bi-plus-lg me-1"></i>Tambah Barang
                        </button>
                        @error('barang_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <hr>
                    <div class="d-flex justify-content-end align-items-center gap-3">
                        <span class="fw-semibold fs-5">Total:</span>
                        <span class="fw-bold fs-4 text-primary" id="totalDisplay">Rp 0</span>
                        <input type="hidden" name="total" id="totalInput" value="0">
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Transaksi</button>
                        <a href="{{ route('transaksis.index') }}" class="btn btn-light"><i class="bi bi-arrow-left me-1"></i>Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 border-start border-4 border-warning">
            <div class="card-body">
                <h6 class="fw-bold text-warning mb-3"><i class="bi bi-info-circle me-1"></i>Info Transaksi</h6>
                <ul class="list-unstyled small text-muted mb-0">
                    <li class="mb-2"><i class="bi bi-check-circle text-success me-1"></i>Pilih pelanggan terlebih dahulu</li>
                    <li class="mb-2"><i class="bi bi-check-circle text-success me-1"></i>Tambah barang sesuai kebutuhan</li>
                    <li class="mb-2"><i class="bi bi-check-circle text-success me-1"></i>Total dihitung otomatis</li>
                    <li class="mb-2"><i class="bi bi-exclamation-triangle text-warning me-1"></i>Jika <strong>hutang</strong>, data hutang dibuat otomatis</li>
                    <li><i class="bi bi-calendar text-primary me-1"></i>Jatuh tempo hutang: <strong>+7 hari</strong></li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const daftarBarang = @json($barangs);

function formatRupiah(angka) {
    return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
}

function hitungTotal() {
    let total = 0;
    document.querySelectorAll('.barang-row').forEach(function (row) {
        const select  = row.querySelector('.barang-select');
        const jumlah  = row.querySelector('.jumlah-input');
        const hargaEl = row.querySelector('.harga-display');
        const subEl   = row.querySelector('.subtotal-display');
        const harga   = parseFloat(select.selectedOptions[0]?.dataset.harga || 0);
        const qty     = parseInt(jumlah.value) || 0;
        const sub     = harga * qty;
        hargaEl.value = harga > 0 ? formatRupiah(harga) : '';
        subEl.value   = sub > 0   ? formatRupiah(sub)   : '';
        total        += sub;
    });
    document.getElementById('totalDisplay').textContent = formatRupiah(total);
    document.getElementById('totalInput').value = total;
}

function pasangEvent(row) {
    row.querySelector('.barang-select').addEventListener('change', hitungTotal);
    row.querySelector('.jumlah-input').addEventListener('input', hitungTotal);
    row.querySelector('.btn-hapus-baris').addEventListener('click', function () {
        if (document.querySelectorAll('.barang-row').length > 1) {
            row.remove();
            hitungTotal();
        } else {
            alert('Minimal harus ada 1 barang!');
        }
    });
}

document.getElementById('btnTambahBarang').addEventListener('click', function () {
    const container = document.getElementById('barangContainer');
    const baris = document.querySelector('.barang-row').cloneNode(true);
    baris.querySelector('.barang-select').value    = '';
    baris.querySelector('.jumlah-input').value     = 1;
    baris.querySelector('.harga-display').value    = '';
    baris.querySelector('.subtotal-display').value = '';
    container.appendChild(baris);
    pasangEvent(baris);
});

document.querySelectorAll('.barang-row').forEach(pasangEvent);
</script>
@endpush
