<?php

namespace App\Http\Controllers;

use App\Models\Hutang;
use Illuminate\Http\Request;

class HutangController extends Controller
{
    public function index(Request $request)
    {
        $query = Hutang::with(['transaksi.customer'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->whereHas('transaksi.customer', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%');
            });
        }

        $hutangs         = $query->paginate(10)->withQueryString();
        $totalSisaHutang = Hutang::where('status', 'belum')->sum('sisa_hutang');

        return view('hutangs.index', compact('hutangs', 'totalSisaHutang'));
    }

    public function show(Hutang $hutang)
    {
        $hutang->load([
            'transaksi.customer',
            'transaksi.detailTransaksis.barang',
            'pembayarans',
        ]);

        return view('hutangs.show', compact('hutang'));
    }

    public function kirimWa(Hutang $hutang)
    {
        $hutang->load('transaksi.customer');

        $customer   = $hutang->transaksi->customer;
        $noWa       = $customer->no_wa;
        $nama       = $customer->nama;
        $sisaHutang = number_format($hutang->sisa_hutang, 0, ',', '.');
        $jatuhTempo = \Carbon\Carbon::parse($hutang->jatuh_tempo)->format('d/m/Y');

        $pesan = "Halo {$nama}, Anda memiliki hutang sebesar Rp {$sisaHutang}. "
               . "Mohon segera bayar sebelum {$jatuhTempo}. Terima kasih.";

        $pesanEncoded = urlencode($pesan);
        $urlWa        = "https://wa.me/{$noWa}?text={$pesanEncoded}";

        return redirect()->away($urlWa);
    }

    public function create() { abort(404); }
    public function store(Request $request) { abort(404); }
    public function edit(Hutang $hutang) { abort(404); }
    public function update(Request $request, Hutang $hutang) { abort(404); }
    public function destroy(Hutang $hutang) { abort(404); }
}
