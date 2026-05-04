<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'total',
        'metode_pembayaran',
        'tanggal'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksi::class);
    }

    public function hutang()
    {
        return $this->hasOne(Hutang::class);
    }
}
