@extends('layouts.app')
@section('title', 'Manajemen Hutang')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#fce4ec"><i class="bi bi-credit-card text-danger"></i></div>
                <div>
                    <div class="text-muted small">Total Sisa Hutang</div>
                    <div class="fw-bold text-danger">Rp {{ number_format($totalSisaHutang, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('hutangs.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-semibold mb-1">Cari Pelanggan</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Nama pelanggan..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">-- Semua Status --</option>
                    <option value="belum" {{ request('status') === 'belum' ? 'selected' : '' }}>Belum Lunas</option>
                    <option value="lunas" {{ request('status') === 'lunas' ? 'selected' : '' }}>Lunas</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-search me-1"></i>Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('hutangs.index') }}" class="btn btn-light btn-sm w-100"><i class="bi bi-x-lg me-1"></i>Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white fw-semibold py-3">
        <i class="bi bi-credit-card me-2 text-danger"></i>Daftar Hutang
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th width="50">#</th><th>Pelanggan</th><th>Total Hutang</th><th>Sisa Hutang</th><th>Jatuh Tempo</th><th>Status</th><th width="130">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($hutangs as $hutang)
                    @php
                        $customer  = $hutang->transaksi->customer;
                        $jt        = \Carbon\Carbon::parse($hutang->jatuh_tempo);
                        $isOverdue = $jt->isPast() && $hutang->status === 'belum';
                    @endphp
                    <tr class="{{ $isOverdue ? 'table-danger' : '' }}">
                        <td>{{ $loop->iteration + ($hutangs->currentPage() - 1) * $hutangs->perPage() }}</td>
                        <td>
                            <div class="fw-semibold">{{ $customer->nama }}</div>
                            <div class="text-muted small">{{ $customer->no_wa }}</div>
                        </td>
                        <td>Rp {{ number_format($hutang->total_hutang, 0, ',', '.') }}</td>
                        <td class="fw-bold {{ $hutang->sisa_hutang > 0 ? 'text-danger' : 'text-success' }}">
                            Rp {{ number_format($hutang->sisa_hutang, 0, ',', '.') }}
                        </td>
                        <td>
                            <span class="badge {{ $isOverdue ? 'bg-danger' : 'bg-secondary' }}">{{ $jt->format('d/m/Y') }}</span>
                            @if($isOverdue)<div class="text-danger small">Terlambat!</div>@endif
                        </td>
                        <td>
                            <span class="badge {{ $hutang->status === 'lunas' ? 'badge-lunas' : 'badge-belum' }}">
                                {{ $hutang->status === 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('hutangs.show', $hutang) }}" class="btn btn-sm btn-outline-primary" title="Detail"><i class="bi bi-eye"></i></a>
                            @if($hutang->status === 'belum')
                            <a href="{{ route('pembayarans.create', ['hutang_id' => $hutang->id]) }}" class="btn btn-sm btn-outline-success" title="Bayar"><i class="bi bi-cash"></i></a>
                            <a href="{{ route('hutangs.kirim-wa', $hutang) }}" class="btn btn-sm btn-outline-success" title="Kirim WA" target="_blank"><i class="bi bi-whatsapp"></i></a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4"><i class="bi bi-inbox fs-4 d-block mb-1"></i>Tidak ada data hutang</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($hutangs->hasPages())
    <div class="card-footer bg-white">{{ $hutangs->links() }}</div>
    @endif
</div>
@endsection
