<?php


namespace App\Http\Controllers;


use App\Models\Penjualan;

class LaporanController
{

    public function index()
    {
        $data = Penjualan::with(['prediksi', 'barang'])->get();
//        $chart = [];
//        foreach ($data as $v) {
//
//        }
//        return $data->toArray();
        return view('admin/laporan/laporan')->with(['data'=> $data]);
    }
}
