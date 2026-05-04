<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'alamat', 'no_wa'];

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function hutangs()
    {
        return $this->hasManyThrough(Hutang::class, Transaksi::class);
    }
}
