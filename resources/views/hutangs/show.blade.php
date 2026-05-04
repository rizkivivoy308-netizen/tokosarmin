@extends('layouts.app')
@section('title', 'Detail Hutang')

@section('content')
<div class="row g-3">
    <div class="col-md-5">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white fw-semibold"><i class="bi bi-person me-2 text-primary"></i>Info Pelanggan</div>
            <div class="card-body">
                @php $customer = $hutang->transaksi->customer; @endphp
                <table class="table table-sm table-borderless mb-0">
                    <tr><td class="text-muted" width="110">Nama</td><td class="fw-semibold">{{ $customer->nama }}</td></tr>
                    <tr><td class="text-muted">Alamat</td><td>{{ $customer->alamat ?? '-' }}</td></tr>
                    <tr>
                        <td class="text-muted">No. WA</td>
                        <td>
                            <a href="https://wa.me/{{ $customer->no_wa }}" target="_blank" class="text-success text-decoration-none">
                                <i class="bi bi-whatsapp me-1"></i>{{ $customer->no_wa }}
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-3 border-start border-4 {{ $hutang->status === 'lunas' ? 'border-success' : 'border-danger' }}">
            <div class="card-header bg-white fw-semibold"><i class="bi bi-credit-card me-2 text-danger"></i>Detail Hutang</div>
            <div class="card-body">
                @php $sudahBayar = $hutang->total_hutang - $hutang->sisa_hutang; @endphp
                <table class="table table-sm table-borderless mb-0">
                    <tr><td class="text-muted" width="130">Total Hutang</td><td>Rp {{ number_format($hutang->total_hutang, 0, ',', '.') }}</td></tr>
                    <tr><td class="text-muted">Total Bayar</td><td class="text-success fw-semibold">Rp {{ number_format($sudahBayar, 0, ',', '.') }}</td></tr>
                    <tr><td class="text-muted">Sisa Hutang</td><td class="fw-bold text-danger fs-5">Rp {{ number_format($hutang->sisa_hutang, 0, ',', '.') }}</td></tr>
                    <tr>
                        <td class="text-muted">Jatuh Tempo</td>
                        <td>
                            @php $jt = \Carbon\Carbon::parse($hutang->jatuh_tempo); @endphp
                            <span class="badge {{ $jt->isPast() && $hutang->status === 'belum' ? 'bg-danger' : 'bg-secondary' }}">{{ $jt->format('d F Y') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            <span class="badge fs-6 {{ $hutang->status === 'lunas' ? 'badge-lunas' : 'badge-belum' }}">
                                {{ $hutang->status === 'lunas' ? '✅ Lunas' : '❌ Belum Lunas' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
            @if($hutang->status === 'belum')
            <div class="card-footer bg-white d-flex gap-2 flex-wrap">
                <a href="{{ route('pembayarans.create', ['hutang_id' => $hutang->id]) }}" class="btn btn-success btn-sm">
                    <i class="bi bi-cash me-1"></i>Bayar Hutang
                </a>
                <a href="{{ route('hutangs.kirim-wa', $hutang) }}" class="btn btn-outline-success btn-sm" target="_blank">
                    <i class="bi bi-whatsapp me-1"></i>Kirim WA Tagihan
                </a>
            </div>
            @else
            <div class="card-footer bg-white">
                <span class="text-success fw-semibold"><i class="bi bi-check-circle-fill me-1"></i>Hutang ini sudah lunas</span>
            </div>
            @endif
        </div>

        <a href="{{ route('hutangs.index') }}" class="btn btn-light btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    </div>

    <div class="col-md-7">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-semibold"><i class="bi bi-clock-history me-2 text-info"></i>Riwayat Pembayaran</div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light"><tr><th>#</th><th>Tanggal Bayar</th><th>Jumlah Bayar</th></tr></thead>
                    <tbody>
                        @forelse($hutang->pembayarans as $i => $bayar)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($bayar->tanggal_bayar)->format('d F Y') }}</td>
                            <td class="text-success fw-semibold">Rp {{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-4"><i class="bi bi-inbox fs-4 d-block mb-1"></i>Belum ada pembayaran</td></tr>
                        @endforelse
                    </tbody>
                    @if($hutang->pembayarans->count() > 0)
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="2" class="text-end fw-bold">Total Dibayar</td>
                            <td class="fw-bold text-success">Rp {{ number_format($hutang->pembayarans->sum('jumlah_bayar'), 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>

        @php
            $persen = $hutang->total_hutang > 0
                ? round((($hutang->total_hutang - $hutang->sisa_hutang) / $hutang->total_hutang) * 100) : 0;
        @endphp
        <div class="card shadow-sm border-0 mt-3">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-1">
                    <span class="small fw-semibold">Progress Pelunasan</span>
                    <span class="small fw-bold">{{ $persen }}%</span>
                </div>
                <div class="progress" style="height:12px;border-radius:6px;">
                    <div class="progress-bar {{ $persen >= 100 ? 'bg-success' : ($persen >= 50 ? 'bg-warning' : 'bg-danger') }}"
                         style="width:{{ $persen }}%;border-radius:6px;"></div>
                </div>
                <div class="d-flex justify-content-between mt-1">
                    <span class="small text-muted">Terbayar: Rp {{ number_format($hutang->total_hutang - $hutang->sisa_hutang, 0, ',', '.') }}</span>
                    <span class="small text-muted">Sisa: Rp {{ number_format($hutang->sisa_hutang, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
