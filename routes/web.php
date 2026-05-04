<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\HutangController;
use App\Http\Controllers\PembayaranController;
use App\Models\Transaksi;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Pelanggan
    Route::resource('customers', CustomerController::class);

    // Barang
    Route::resource('barangs', BarangController::class);

    // Transaksi
    Route::resource('transaksis', TransaksiController::class);

    // Hutang
    Route::resource('hutangs', HutangController::class);

    Route::get('/hutangs/{hutang}/kirim-wa', [HutangController::class, 'kirimWa'])
        ->name('hutangs.kirim-wa');

    // Pembayaran
    Route::get('/pembayarans/create', [PembayaranController::class, 'create'])->name('pembayarans.create');
    Route::post('/pembayarans', [PembayaranController::class, 'store'])->name('pembayarans.store');
    Route::get('/pembayarans', [PembayaranController::class, 'index'])->name('pembayarans.index');
    Route::get('/pembayarans/{pembayaran}', [PembayaranController::class, 'show'])->name('pembayarans.show');

    // Laporan
    Route::prefix('laporan')->name('laporan.')->group(function () {

        Route::get('/transaksi', function () {
            $transaksis = \App\Models\Transaksi::with('customer')->latest()->paginate(20);
            $totalPendapatan = \App\Models\Transaksi::sum('total');

            return view('laporan.transaksi', compact('transaksis', 'totalPendapatan'));
        })->name('transaksi');

        Route::get('/hutang', function () {
            $hutangs = \App\Models\Hutang::with('transaksi.customer')->latest()->paginate(20);
            $totalHutang = \App\Models\Hutang::sum('total_hutang');
            $totalSisaHutang = \App\Models\Hutang::where('status', 'belum')->sum('sisa_hutang');
            $totalLunas = \App\Models\Hutang::where('status', 'lunas')->count();
            $totalBelumLunas = \App\Models\Hutang::where('status', 'belum')->count();

            return view('laporan.hutang', compact(
                'hutangs',
                'totalHutang',
                'totalSisaHutang',
                'totalLunas',
                'totalBelumLunas'
            ));
        })->name('hutang');

        Route::get('/pembayaran', function () {
            $pembayarans = \App\Models\Pembayaran::with('hutang.transaksi.customer')->latest()->paginate(20);
            $totalPembayaran = \App\Models\Pembayaran::sum('jumlah_bayar');

            return view('laporan.pembayaran', compact('pembayarans', 'totalPembayaran'));
        })->name('pembayaran');

    });

});

// ===============================
// ROUTE PRINT NOTA (FIX FINAL)
// ===============================
Route::get('/transaksis/{transaksi}/print', function (Transaksi $transaksi) {
    $transaksi->load([
        'customer',
        'detailTransaksis.barang',
        'hutang',
    ]);

    return view('transaksis.print', compact('transaksi'));
})->name('transaksis.print')->middleware('auth');

require __DIR__ . '/auth.php';