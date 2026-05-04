<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['nama' => 'Budi Santoso',   'alamat' => 'Jl. Merdeka No. 10, Surakarta',       'no_wa' => '6281234567890'],
            ['nama' => 'Siti Rahayu',    'alamat' => 'Jl. Diponegoro No. 5, Surakarta',      'no_wa' => '6282345678901'],
            ['nama' => 'Agus Prasetyo',  'alamat' => 'Jl. Sudirman No. 22, Surakarta',       'no_wa' => '6283456789012'],
            ['nama' => 'Dewi Lestari',   'alamat' => 'Jl. Ahmad Yani No. 8, Surakarta',      'no_wa' => '6284567890123'],
            ['nama' => 'Hendra Wijaya',  'alamat' => 'Jl. Gatot Subroto No. 15, Surakarta',  'no_wa' => '6285678901234'],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
