@extends('layouts.app')
@section('title', 'Riwayat Pembayaran')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-semibold"><i class="bi bi-cash-coin me-2 text-success"></i>Riwayat Pembayaran</h5>
        <div class="fw-semibold text-success">
            Total: Rp {{ number_format($pembayarans->sum('jumlah_bayar'), 0, ',', '.') }}
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Tanggal</th><th>Pelanggan</th><th>Jumlah Bayar</th><th>Sisa Hutang</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($pembayarans as $bayar)
                    <tr>
                        <td>{{ $loop->iteration + ($pembayarans->currentPage() - 1) * $pembayarans->perPage() }}</td>
                        <td>{{ \Carbon\Carbon::parse($bayar->tanggal_bayar)->format('d/m/Y') }}</td>
                        <td class="fw-semibold">{{ $bayar->hutang->transaksi->customer->nama }}</td>
                        <td class="text-success fw-semibold">Rp {{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}</td>
                        <td class="{{ $bayar->hutang->sisa_hutang > 0 ? 'text-danger' : 'text-success' }}">
                            Rp {{ number_format($bayar->hutang->sisa_hutang, 0, ',', '.') }}
                        </td>
                        <td>
                            <a href="{{ route('hutangs.show', $bayar->hutang_id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-4 d-block mb-1"></i>Belum ada riwayat pembayaran
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($pembayarans->hasPages())
    <div class="card-footer bg-white">{{ $pembayarans->links() }}</div>
    @endif
</div>
@endsection
