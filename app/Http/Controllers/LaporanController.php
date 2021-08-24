<?php


namespace App\Http\Controllers;


use App\Models\Barang;
use App\Models\Penjualan;

class LaporanController
{

    public function index()
    {
        $data = Penjualan::with(['prediksi', 'barang'])->get();
        $barang = Barang::all();
        return view('admin/laporan/laporan')->with(['data'=> $data, 'barang' => $barang]);
    }
}
