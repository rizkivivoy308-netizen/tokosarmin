<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $barangs = [
            ['nama_barang' => 'Beras 5kg',          'harga' => 65000, 'stok' => 50],
            ['nama_barang' => 'Minyak Goreng 1L',   'harga' => 18000, 'stok' => 40],
            ['nama_barang' => 'Gula Pasir 1kg',     'harga' => 14000, 'stok' => 60],
            ['nama_barang' => 'Tepung Terigu 1kg',  'harga' => 12000, 'stok' => 35],
            ['nama_barang' => 'Mie Instan',         'harga' =>  3500, 'stok' => 100],
            ['nama_barang' => 'Telur Ayam 1kg',     'harga' => 28000, 'stok' => 30],
            ['nama_barang' => 'Sabun Mandi',        'harga' =>  4500, 'stok' => 80],
            ['nama_barang' => 'Shampoo Sachet',     'harga' =>  1500, 'stok' => 120],
            ['nama_barang' => 'Kecap Manis 135ml',  'harga' =>  8000, 'stok' => 45],
            ['nama_barang' => 'Garam 500gr',        'harga' =>  3000, 'stok' => 70],
        ];

        foreach ($barangs as $barang) {
            Barang::create($barang);
        }
    }
}
