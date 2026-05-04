@extends('layouts.app')
@section('title', 'Laporan Hutang')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body py-3">
                <div class="text-muted small mb-1">Total Hutang</div>
                <div class="fw-bold text-danger">Rp {{ number_format($totalHutang, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body py-3">
                <div class="text-muted small mb-1">Sisa Belum Lunas</div>
                <div class="fw-bold text-warning">Rp {{ number_format($totalSisaHutang, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body py-3">
                <div class="text-muted small mb-1">Sudah Lunas</div>
                <div class="fw-bold text-success fs-4">{{ $totalLunas }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body py-3">
                <div class="text-muted small mb-1">Belum Lunas</div>
                <div class="fw-bold text-danger fs-4">{{ $totalBelumLunas }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-semibold"><i class="bi bi-file-earmark-minus me-2 text-danger"></i>Laporan Hutang Pelanggan</h5>
        <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-printer me-1"></i>Cetak
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Pelanggan</th><th>Tgl Transaksi</th><th>Total Hutang</th><th>Sudah Bayar</th><th>Sisa Hutang</th><th>Jatuh Tempo</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse($hutangs as $hutang)
                    @php
                        $customer   = $hutang->transaksi->customer;
                        $sudahBayar = $hutang->total_hutang - $hutang->sisa_hutang;
                        $jt         = \Carbon\Carbon::parse($hutang->jatuh_tempo);
                        $isOverdue  = $jt->isPast() && $hutang->status === 'belum';
                    @endphp
                    <tr class="{{ $isOverdue ? 'table-danger' : '' }}">
                        <td>{{ $loop->iteration + ($hutangs->currentPage() - 1) * $hutangs->perPage() }}</td>
                        <td>
                            <div class="fw-semibold">{{ $customer->nama }}</div>
                            <div class="text-muted small">{{ $customer->no_wa }}</div>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($hutang->transaksi->tanggal)->format('d/m/Y') }}</td>
                        <td>Rp {{ number_format($hutang->total_hutang, 0, ',', '.') }}</td>
                        <td class="text-success">Rp {{ number_format($sudahBayar, 0, ',', '.') }}</td>
                        <td class="fw-bold {{ $hutang->sisa_hutang > 0 ? 'text-danger' : 'text-success' }}">
                            Rp {{ number_format($hutang->sisa_hutang, 0, ',', '.') }}
                        </td>
                        <td>
                            <span class="badge {{ $isOverdue ? 'bg-danger' : 'bg-secondary' }}">{{ $jt->format('d/m/Y') }}</span>
                        </td>
                        <td>
                            <span class="badge {{ $hutang->status === 'lunas' ? 'badge-lunas' : 'badge-belum' }}">
                                {{ $hutang->status === 'lunas' ? 'Lunas' : 'Belum' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4"><i class="bi bi-inbox fs-4 d-block mb-1"></i>Belum ada data hutang</td></tr>
                    @endforelse
                </tbody>
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="3" class="text-end">Total:</td>
                        <td>Rp {{ number_format($totalHutang, 0, ',', '.') }}</td>
                        <td></td>
                        <td class="text-danger">Rp {{ number_format($totalSisaHutang, 0, ',', '.') }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @if($hutangs->hasPages())
    <div class="card-footer bg-white">{{ $hutangs->links() }}</div>
    @endif
</div>
@endsection
