<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prediksi extends Model
{
    use HasFactory;
    protected $table = 'prediksi';

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
