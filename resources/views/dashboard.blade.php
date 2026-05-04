@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#e8eaf6">
                    <i class="bi bi-people text-primary"></i>
                </div>
                <div>
                    <div class="text-muted small">Pelanggan</div>
                    <div class="fw-bold fs-4">{{ $totalCustomer }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#e8f5e9">
                    <i class="bi bi-box-seam text-success"></i>
                </div>
                <div>
                    <div class="text-muted small">Barang</div>
                    <div class="fw-bold fs-4">{{ $totalBarang }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#fff3e0">
                    <i class="bi bi-receipt text-warning"></i>
                </div>
                <div>
                    <div class="text-muted small">Transaksi</div>
                    <div class="fw-bold fs-4">{{ $totalTransaksi }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#fce4ec">
                    <i class="bi bi-credit-card text-danger"></i>
                </div>
                <div>
                    <div class="text-muted small">Hutang Aktif</div>
                    <div class="fw-bold fs-4">{{ $totalHutangAktif }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4 col-lg">
        <div class="card stat-card shadow-sm h-100 border-danger">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#ffebee">
                    <i class="bi bi-cash-stack text-danger"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Sisa Hutang</div>
                    <div class="fw-bold text-danger">
                        Rp {{ number_format($totalSisaHutang, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-semibold border-bottom d-flex justify-content-between">
                <span><i class="bi bi-credit-card me-2 text-danger"></i>Hutang Belum Lunas</span>
                <a href="{{ route('hutangs.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Pelanggan</th><th>Sisa Hutang</th><th>Jatuh Tempo</th></tr>
                    </thead>
                    <tbody>
                        @forelse($hutangTerbaru as $hutang)
                        <tr>
                            <td>{{ $hutang->transaksi->customer->nama }}</td>
                            <td class="text-danger fw-semibold">Rp {{ number_format($hutang->sisa_hutang, 0, ',', '.') }}</td>
                            <td>
                                @php $jt = \Carbon\Carbon::parse($hutang->jatuh_tempo); @endphp
                                <span class="badge {{ $jt->isPast() ? 'bg-danger' : 'bg-warning text-dark' }}">
                                    {{ $jt->format('d/m/Y') }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">Tidak ada hutang aktif 🎉</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-semibold border-bottom d-flex justify-content-between">
                <span><i class="bi bi-receipt me-2 text-primary"></i>Transaksi Terbaru</span>
                <a href="{{ route('transaksis.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Pelanggan</th><th>Total</th><th>Metode</th></tr>
                    </thead>
                    <tbody>
                        @forelse($transaksiTerbaru as $trx)
                        <tr>
                            <td>{{ $trx->customer->nama }}</td>
                            <td>Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge {{ $trx->metode_pembayaran === 'cash' ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ ucfirst($trx->metode_pembayaran) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">Belum ada transaksi</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
