<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hutang extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_id',
        'total_hutang',
        'sisa_hutang',
        'status',
        'jatuh_tempo'
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function customer()
    {
        return $this->transaksi->customer;
    }
}
