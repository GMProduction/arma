<?php


namespace App\Http\Controllers;


use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController
{
    public function index()
    {
        $data = Barang::all();
        return view('admin/barang/barang')->with(['data' => $data]);
    }

    public function add(Request $request)
    {
        try {
            $barang = new Barang();
            $barang->nama = $request->request->get('nama');
            $barang->harga = $request->request->get('harga');
            $barang->satuan = $request->request->get('satuan');
            $barang->qty = 0;
            $barang->save();
            return redirect('/admin/barang')->with('success', 'success menambahkan barang');
        }catch (\Exception $e){
            return redirect('/admin/barang')->with('gagal', 'success menambahkan barang');
        }
    }

    public function edit(Request $request)
    {
        try {
            $barang = Barang::find($request->request->get('id'));
            $barang->nama = $request->request->get('nama');
            $barang->harga = $request->request->get('harga');
            $barang->satuan = $request->request->get('satuan');
            $barang->qty = 0;
            $barang->save();
            return redirect('/admin/barang')->with('success', 'success menambahkan barang');
        }catch (\Exception $e){
            return redirect('/admin/barang')->with('failed', 'gagal menambahkan barang');
        }
    }

    public function destroy($id)
    {
        try {
            Barang::destroy($id);
            return response()->json('success', 200);
        }catch (\Exception $e){
            return response()->json('failed', 500);
        }
    }
}
