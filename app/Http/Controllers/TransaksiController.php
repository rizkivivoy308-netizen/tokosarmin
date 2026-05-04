<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Customer;
use App\Models\DetailTransaksi;
use App\Models\Hutang;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with(['customer', 'hutang'])
            ->latest()
            ->paginate(10);

        return view('transaksis.index', compact('transaksis'));
    }

    public function create()
    {
        $customers = Customer::orderBy('nama')->get();
        $barangs   = Barang::where('stok', '>', 0)->orderBy('nama_barang')->get();

        return view('transaksis.create', compact('customers', 'barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id'       => 'required|exists:customers,id',
            'metode_pembayaran' => 'required|in:cash,hutang',
            'barang_id'         => 'required|array|min:1',
            'barang_id.*'       => 'exists:barangs,id',
            'jumlah'            => 'required|array|min:1',
            'jumlah.*'          => 'integer|min:1',
        ], [
            'customer_id.required'       => 'Pilih pelanggan terlebih dahulu.',
            'metode_pembayaran.required' => 'Pilih metode pembayaran.',
            'barang_id.required'         => 'Pilih minimal satu barang.',
            'jumlah.*.min'               => 'Jumlah barang minimal 1.',
        ]);

        DB::beginTransaction();

        try {
            $totalTransaksi = 0;
            $detailItems    = [];

            foreach ($request->barang_id as $index => $barangId) {
                $barang = Barang::findOrFail($barangId);
                $jumlah = $request->jumlah[$index];

                if ($barang->stok < $jumlah) {
                    return back()
                        ->with('error', "Stok barang '{$barang->nama_barang}' tidak cukup! Sisa stok: {$barang->stok}")
                        ->withInput();
                }

                $subtotal        = $barang->harga * $jumlah;
                $totalTransaksi += $subtotal;

                $detailItems[] = [
                    'barang'   => $barang,
                    'jumlah'   => $jumlah,
                    'subtotal' => $subtotal,
                ];
            }

            $transaksi = Transaksi::create([
                'customer_id'       => $request->customer_id,
                'total'             => $totalTransaksi,
                'metode_pembayaran' => $request->metode_pembayaran,
                'tanggal'           => now()->toDateString(),
            ]);

            foreach ($detailItems as $item) {
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'barang_id'    => $item['barang']->id,
                    'jumlah'       => $item['jumlah'],
                    'subtotal'     => $item['subtotal'],
                ]);

                $item['barang']->decrement('stok', $item['jumlah']);
            }

            if ($request->metode_pembayaran === 'hutang') {
                Hutang::create([
                    'transaksi_id' => $transaksi->id,
                    'total_hutang' => $totalTransaksi,
                    'sisa_hutang'  => $totalTransaksi,
                    'status'       => 'belum',
                    'jatuh_tempo'  => now()->addDays(7)->toDateString(),
                ]);
            }

            DB::commit();

            return redirect()->route('transaksis.show', $transaksi->id)
                ->with('success', 'Transaksi berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Transaksi $transaksi)
    {
        $transaksi->load([
            'customer',
            'detailTransaksis.barang',
            'hutang.pembayarans',
        ]);

        return view('transaksis.show', compact('transaksi'));
    }

    public function edit(Transaksi $transaksi)
    {
        return redirect()->route('transaksis.index')
            ->with('error', 'Transaksi tidak dapat diedit.');
    }

    public function update(Request $request, Transaksi $transaksi)
    {
        return redirect()->route('transaksis.index')
            ->with('error', 'Transaksi tidak dapat diubah.');
    }

    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();

        return redirect()->route('transaksis.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }
}
