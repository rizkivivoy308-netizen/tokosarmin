@extends('layouts.app')
@section('title', 'Laporan Transaksi')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#e8eaf6"><i class="bi bi-receipt text-primary"></i></div>
                <div>
                    <div class="text-muted small">Total Transaksi</div>
                    <div class="fw-bold fs-4">{{ $transaksis->total() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#e8f5e9"><i class="bi bi-cash-stack text-success"></i></div>
                <div>
                    <div class="text-muted small">Total Pendapatan</div>
                    <div class="fw-bold text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#fff3e0"><i class="bi bi-calendar-check text-warning"></i></div>
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
        <h5 class="mb-0 fw-semibold"><i class="bi bi-file-earmark-bar-graph me-2 text-primary"></i>Laporan Transaksi Penjualan</h5>
        <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-printer me-1"></i>Cetak
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Tanggal</th><th>Pelanggan</th><th>Total</th><th>Metode</th><th>Status Hutang</th></tr>
                </thead>
                <tbody>
                    @forelse($transaksis as $trx)
                    <tr>
                        <td>{{ $loop->iteration + ($transaksis->currentPage() - 1) * $transaksis->perPage() }}</td>
                        <td>{{ \Carbon\Carbon::parse($trx->tanggal)->format('d/m/Y') }}</td>
                        <td class="fw-semibold">{{ $trx->customer->nama }}</td>
                        <td>Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $trx->metode_pembayaran === 'cash' ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ ucfirst($trx->metode_pembayaran) }}
                            </span>
                        </td>
                        <td>
                            @if($trx->hutang)
                                <span class="badge {{ $trx->hutang->status === 'lunas' ? 'badge-lunas' : 'badge-belum' }}">
                                    {{ $trx->hutang->status === 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                                </span>
                            @else
                                <span class="text-muted">Cash</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4"><i class="bi bi-inbox fs-4 d-block mb-1"></i>Belum ada data transaksi</td></tr>
                    @endforelse
                </tbody>
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="3" class="text-end">Total Pendapatan:</td>
                        <td class="text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @if($transaksis->hasPages())
    <div class="card-footer bg-white">{{ $transaksis->links() }}</div>
    @endif
</div>
@endsection
