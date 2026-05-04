<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'hutang_id',
        'jumlah_bayar',
        'tanggal_bayar'
    ];

    public function hutang()
    {
        return $this->belongsTo(Hutang::class);
    }
}
