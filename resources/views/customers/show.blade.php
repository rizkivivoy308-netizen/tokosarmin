@extends('layouts.app')
@section('title', 'Detail Pelanggan')

@section('content')
<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-4">
                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                     style="width:64px;height:64px;font-size:1.8rem">
                    {{ strtoupper(substr($customer->nama, 0, 1)) }}
                </div>
                <h5 class="fw-bold mb-1">{{ $customer->nama }}</h5>
                <p class="text-muted small mb-2">{{ $customer->alamat ?? '-' }}</p>
                <a href="https://wa.me/{{ $customer->no_wa }}" target="_blank" class="btn btn-success btn-sm">
                    <i class="bi bi-whatsapp me-1"></i>{{ $customer->no_wa }}
                </a>
            </div>
            <div class="card-footer bg-white text-center">
                <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning btn-sm me-1">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
                <a href="{{ route('customers.index') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-receipt me-2 text-primary"></i>Riwayat Transaksi
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr><th>Tanggal</th><th>Total</th><th>Metode</th><th>Status Hutang</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @forelse($transaksis as $trx)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($trx->tanggal)->format('d/m/Y') }}</td>
                            <td>Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge {{ $trx->metode_pembayaran === 'cash' ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ ucfirst($trx->metode_pembayaran) }}
                                </span>
                            </td>
                            <td>
                                @if($trx->hutang)
                                    <span class="badge {{ $trx->hutang->status === 'lunas' ? 'badge-lunas' : 'badge-belum' }}">
                                        {{ ucfirst($trx->hutang->status) }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('transaksis.show', $trx) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">Belum ada transaksi</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
