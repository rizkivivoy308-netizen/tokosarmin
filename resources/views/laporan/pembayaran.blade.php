@extends('layouts.app')
@section('title', 'Laporan Pembayaran')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#e8f5e9"><i class="bi bi-cash-coin text-success"></i></div>
                <div>
                    <div class="text-muted small">Total Pembayaran</div>
                    <div class="fw-bold text-success fs-5">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#e8eaf6"><i class="bi bi-list-ol text-primary"></i></div>
                <div>
                    <div class="text-muted small">Jumlah Transaksi Bayar</div>
                    <div class="fw-bold fs-4">{{ $pembayarans->total() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#fff3e0"><i class="bi bi-calendar text-warning"></i></div>
                <div>
                    <div class="text-muted small">Tanggal Cetak</div>
                    <div class="fw-semibold">{{ \Carbon\Carbon::now()->format('d F Y') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-semibold"><i class="bi bi-file-earmark-check me-2 text-success"></i>Laporan Pembayaran Hutang</h5>
        <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-printer me-1"></i>Cetak
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Tgl Bayar</th><th>Pelanggan</th><th>Jumlah Bayar</th><th>Total Hutang</th><th>Sisa Hutang</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse($pembayarans as $bayar)
                    <tr>
                        <td>{{ $loop->iteration + ($pembayarans->currentPage() - 1) * $pembayarans->perPage() }}</td>
                        <td>{{ \Carbon\Carbon::parse($bayar->tanggal_bayar)->format('d/m/Y') }}</td>
                        <td class="fw-semibold">{{ $bayar->hutang->transaksi->customer->nama }}</td>
                        <td class="text-success fw-semibold">Rp {{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($bayar->hutang->total_hutang, 0, ',', '.') }}</td>
                        <td class="{{ $bayar->hutang->sisa_hutang > 0 ? 'text-danger' : 'text-success' }}">
                            Rp {{ number_format($bayar->hutang->sisa_hutang, 0, ',', '.') }}
                        </td>
                        <td>
                            <span class="badge {{ $bayar->hutang->status === 'lunas' ? 'badge-lunas' : 'badge-belum' }}">
                                {{ $bayar->hutang->status === 'lunas' ? 'Lunas' : 'Belum' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4"><i class="bi bi-inbox fs-4 d-block mb-1"></i>Belum ada data pembayaran</td></tr>
                    @endforelse
                </tbody>
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="3" class="text-end">Total Pembayaran:</td>
                        <td class="text-success">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @if($pembayarans->hasPages())
    <div class="card-footer bg-white">{{ $pembayarans->links() }}</div>
    @endif
</div>
@endsection
