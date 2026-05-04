@extends('layouts.app')
@section('title', 'Manajemen Barang')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-semibold"><i class="bi bi-box-seam me-2 text-success"></i>Data Barang</h5>
        <a href="{{ route('barangs.create') }}" class="btn btn-success btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Tambah Barang
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th width="50">#</th><th>Nama Barang</th><th>Harga</th><th>Stok</th><th width="120">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($barangs as $barang)
                    <tr>
                        <td>{{ $loop->iteration + ($barangs->currentPage() - 1) * $barangs->perPage() }}</td>
                        <td class="fw-semibold">{{ $barang->nama_barang }}</td>
                        <td>Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $barang->stok > 10 ? 'bg-success' : ($barang->stok > 0 ? 'bg-warning text-dark' : 'bg-danger') }}">
                                {{ $barang->stok }} pcs
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('barangs.edit', $barang) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('barangs.destroy', $barang) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus barang ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-4"><i class="bi bi-inbox fs-4 d-block mb-1"></i>Belum ada data barang</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($barangs->hasPages())
    <div class="card-footer bg-white">{{ $barangs->links() }}</div>
    @endif
</div>
@endsection
