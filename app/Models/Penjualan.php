<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;
    protected $table = 'penjualan';

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function prediksi()
    {
        return $this->hasOne(Prediksi::class, 'penjualan_id');
    }
}
