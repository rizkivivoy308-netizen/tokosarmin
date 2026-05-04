<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\Hutang;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCustomer    = Customer::count();
        $totalBarang      = Barang::count();
        $totalTransaksi   = Transaksi::count();
        $totalHutangAktif = Hutang::where('status', 'belum')->count();
        $totalSisaHutang  = Hutang::where('status', 'belum')->sum('sisa_hutang');

        $hutangTerbaru = Hutang::with('transaksi.customer')
            ->where('status', 'belum')
            ->latest()
            ->take(5)
            ->get();

        $transaksiTerbaru = Transaksi::with('customer')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalCustomer',
            'totalBarang',
            'totalTransaksi',
            'totalHutangAktif',
            'totalSisaHutang',
            'hutangTerbaru',
            'transaksiTerbaru'
        ));
    }
}
