@extends('layouts.app')
@section('title', 'Transaksi Penjualan')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-semibold"><i class="bi bi-receipt me-2 text-primary"></i>Data Transaksi</h5>
        <a href="{{ route('transaksis.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Transaksi Baru
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th width="50">#</th><th>Tanggal</th><th>Pelanggan</th><th>Total</th><th>Metode</th><th>Status Hutang</th><th width="100">Aksi</th></tr>
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
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('transaksis.show', $trx) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                            <form action="{{ route('transaksis.destroy', $trx) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus transaksi ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4"><i class="bi bi-inbox fs-4 d-block mb-1"></i>Belum ada transaksi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($transaksis->hasPages())
    <div class="card-footer bg-white">{{ $transaksis->links() }}</div>
    @endif
</div>
@endsection
