@extends('layouts.app')
@section('title', 'Detail Transaksi')

@section('content')
<div class="row g-3">
    <div class="col-md-5">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-receipt me-2 text-primary"></i>Info Transaksi #{{ $transaksi->id }}
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr><td class="text-muted" width="130">Pelanggan</td><td><strong>{{ $transaksi->customer->nama }}</strong></td></tr>
                    <tr><td class="text-muted">Tanggal</td><td>{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d F Y') }}</td></tr>
                    <tr>
                        <td class="text-muted">Metode</td>
                        <td>
                            <span class="badge {{ $transaksi->metode_pembayaran === 'cash' ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ ucfirst($transaksi->metode_pembayaran) }}
                            </span>
                        </td>
                    </tr>
                    <tr><td class="text-muted">Total</td><td class="fw-bold text-primary fs-5">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td></tr>
                </table>
            </div>
            <div class="card-footer bg-white">
                <a href="{{ route('transaksis.print', $transaksi) }}"
                    target="_blank"
                    class="btn btn-sm btn-warning me-1">
    <i class="bi bi-printer me-1"></i>Print Nota
</a>
                <a href="{{ route('transaksis.index') }}" class="btn btn-sm btn-light"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
            </div>
        </div>

        @if($transaksi->hutang)
        <div class="card shadow-sm border-0 border-start border-4 {{ $transaksi->hutang->status === 'lunas' ? 'border-success' : 'border-danger' }}">
            <div class="card-header bg-white fw-semibold"><i class="bi bi-credit-card me-2 text-danger"></i>Info Hutang</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr><td class="text-muted" width="130">Total Hutang</td><td>Rp {{ number_format($transaksi->hutang->total_hutang, 0, ',', '.') }}</td></tr>
                    <tr><td class="text-muted">Sisa Hutang</td><td class="fw-bold text-danger">Rp {{ number_format($transaksi->hutang->sisa_hutang, 0, ',', '.') }}</td></tr>
                    <tr>
                        <td class="text-muted">Jatuh Tempo</td>
                        <td>
                            @php $jt = \Carbon\Carbon::parse($transaksi->hutang->jatuh_tempo); @endphp
                            <span class="badge {{ $jt->isPast() && $transaksi->hutang->status === 'belum' ? 'bg-danger' : 'bg-secondary' }}">
                                {{ $jt->format('d/m/Y') }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td><span class="badge {{ $transaksi->hutang->status === 'lunas' ? 'badge-lunas' : 'badge-belum' }}">{{ ucfirst($transaksi->hutang->status) }}</span></td>
                    </tr>
                </table>
            </div>
            @if($transaksi->hutang->status === 'belum')
            <div class="card-footer bg-white d-flex gap-2">
                <a href="{{ route('pembayarans.create', ['hutang_id' => $transaksi->hutang->id]) }}" class="btn btn-sm btn-success">
                    <i class="bi bi-cash me-1"></i>Bayar Hutang
                </a>
                <a href="{{ route('hutangs.kirim-wa', $transaksi->hutang->id) }}" class="btn btn-sm btn-outline-success" target="_blank">
                    <i class="bi bi-whatsapp me-1"></i>Kirim WA
                </a>
            </div>
            @endif
        </div>
        @endif
    </div>

    <div class="col-md-7">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-semibold"><i class="bi bi-list-ul me-2 text-success"></i>Detail Barang</div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr><th>#</th><th>Nama Barang</th><th>Harga Satuan</th><th>Jumlah</th><th>Subtotal</th></tr>
                    </thead>
                    <tbody>
                        @foreach($transaksi->detailTransaksis as $i => $detail)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td class="fw-semibold">{{ $detail->barang->nama_barang }}</td>
                            <td>Rp {{ number_format($detail->barang->harga, 0, ',', '.') }}</td>
                            <td>{{ $detail->jumlah }} pcs</td>
                            <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Total</td>
                            <td class="fw-bold text-primary">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        @if($transaksi->hutang && $transaksi->hutang->pembayarans->count() > 0)
        <div class="card shadow-sm border-0 mt-3">
            <div class="card-header bg-white fw-semibold"><i class="bi bi-clock-history me-2 text-info"></i>Riwayat Pembayaran</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="table-light"><tr><th>#</th><th>Tanggal Bayar</th><th>Jumlah</th></tr></thead>
                    <tbody>
                        @foreach($transaksi->hutang->pembayarans as $i => $bayar)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($bayar->tanggal_bayar)->format('d/m/Y') }}</td>
                            <td class="text-success fw-semibold">Rp {{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
