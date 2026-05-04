<?php

namespace App\Http\Controllers;

use App\Models\Hutang;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayarans = Pembayaran::with(['hutang.transaksi.customer'])
            ->latest()
            ->paginate(10);

        return view('pembayarans.index', compact('pembayarans'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'hutang_id' => 'required|exists:hutangs,id',
        ]);

        $hutang = Hutang::with('transaksi.customer')->findOrFail($request->hutang_id);

        if ($hutang->status === 'lunas') {
            return redirect()->route('hutangs.show', $hutang->id)
                ->with('error', 'Hutang ini sudah lunas!');
        }

        return view('pembayarans.create', compact('hutang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hutang_id'    => 'required|exists:hutangs,id',
            'jumlah_bayar' => 'required|numeric|min:1',
        ], [
            'hutang_id.required'    => 'Data hutang tidak valid.',
            'jumlah_bayar.required' => 'Jumlah bayar wajib diisi.',
            'jumlah_bayar.numeric'  => 'Jumlah bayar harus berupa angka.',
            'jumlah_bayar.min'      => 'Jumlah bayar minimal Rp 1.',
        ]);

        DB::beginTransaction();

        try {
            $hutang      = Hutang::findOrFail($request->hutang_id);
            $jumlahBayar = $request->jumlah_bayar;

            if ($jumlahBayar > $hutang->sisa_hutang) {
                return back()
                    ->with('error', 'Jumlah bayar melebihi sisa hutang! Sisa hutang: Rp '
                        . number_format($hutang->sisa_hutang, 0, ',', '.'))
                    ->withInput();
            }

            Pembayaran::create([
                'hutang_id'    => $hutang->id,
                'jumlah_bayar' => $jumlahBayar,
                'tanggal_bayar' => now()->toDateString(),
            ]);

            $sisaBaru            = $hutang->sisa_hutang - $jumlahBayar;
            $hutang->sisa_hutang = $sisaBaru;

            if ($sisaBaru <= 0) {
                $hutang->status      = 'lunas';
                $hutang->sisa_hutang = 0;
            }

            $hutang->save();

            DB::commit();

            $pesan = $hutang->status === 'lunas'
                ? 'Pembayaran berhasil! Hutang sudah LUNAS.'
                : 'Cicilan berhasil disimpan! Sisa hutang: Rp ' . number_format($hutang->sisa_hutang, 0, ',', '.');

            return redirect()->route('hutangs.show', $hutang->id)
                ->with('success', $pesan);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Pembayaran $pembayaran)
    {
        $pembayaran->load('hutang.transaksi.customer');
        return view('pembayarans.show', compact('pembayaran'));
    }

    public function edit(Pembayaran $pembayaran) { abort(404); }
    public function update(Request $request, Pembayaran $pembayaran) { abort(404); }
    public function destroy(Pembayaran $pembayaran) { abort(404); }
}
