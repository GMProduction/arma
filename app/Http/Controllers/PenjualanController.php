<?php


namespace App\Http\Controllers;


use App\Models\Barang;
use App\Models\Penjualan;
use Illuminate\Http\Request;

class PenjualanController
{
    public function index()
    {
        $data = Penjualan::with('barang')->get();
        $barang = Barang::all();
        return view('admin/penjualan/penjualan')->with(['data' => $data, 'barang' => $barang]);
    }


    public function add(Request $request)
    {
        try {
            $idBarang = $request->request->get('id');
            $qty = $request->request->get('qty');
            $barang = Barang::find($idBarang);
            $harga = $barang->harga;
            $total = $harga * $qty;
            $lastPenjualan = Penjualan::where('barang_id', '=', $idBarang)->orderBy('minggu', 'DESC')->first();
            $mingguKe = 1;
            if ($lastPenjualan) {
                $mingguKe = $lastPenjualan->minggu + 1;
            }
            $penjualan = new Penjualan();
            $penjualan->minggu = $mingguKe;
            $penjualan->barang_id = $idBarang;
            $penjualan->harga = $harga;
            $penjualan->qty = $qty;
            $penjualan->total = $total;
            $penjualan->save();
            return response()->json('Success', 200);
        } catch (\Exception $e) {
            return response()->json('Error ' . $e, 500);
        }

    }

    public function edit(Request $request)
    {
        try {
            $id = $request->request->get('id');
            $qty = $request->request->get('qty');
            $penjualan = Penjualan::find($id);;
            $harga = $penjualan->harga;
            $total = $qty * $harga;
            $penjualan->qty = $qty;
            $penjualan->total = $total;
            $penjualan->save();
            return response()->json('Success', 200);
        } catch (\Exception $e) {
            return response()->json('Error ' . $e, 500);
        }
    }
    public function hitung()
    {
        $constant = 12;
        $data = Penjualan::orderBy('minggu', 'DESC')->take($constant)->get();
        $peramalan = [];
        $sumYt = 0;
        $sumXt = 0;
        $sumXY = 0;
        $sumX2 = 0;
        for ($i = 0; $i < $constant; $i++) {
            $minggu = $data[$i]->minggu;
            $yt = $data[$i]->qty;
            $xt = $i === ($constant - 1) ? 0 : $data[$i + 1]->qty;
            $xy = $yt * $xt;
            $x2 = pow($xt, 2);
            $sumYt = $sumYt + $yt;
            $sumXt = $sumXt + $xt;
            $sumXY = $sumXY + $xy;
            $sumX2 = $sumX2 + $x2;
            $temp = [
                'periode' => $minggu,
                'yt' => $yt,
                'xt' => $xt,
                'xy' => $xy,
                'x2' => $x2
            ];
            array_unshift($peramalan, $temp);
        }

        $summary = [
            'yt' => $sumYt,
            'xt' => $sumXt,
            'xy' => $sumXY,
            'x2' => $sumX2
        ];

        $tempHimpunan = (($constant * $sumXY) - ($sumXt * $sumYt)) / (($constant * $sumX2) - (pow($sumXt, 2)));
        $himpunan = round($tempHimpunan, 5);
        $tempHimpunanKeDua = ($sumYt - ($himpunan * $sumXt)) / $constant;
        $himpunanKeDua = round($tempHimpunanKeDua, 5);
        $prediksi = ($himpunan * $peramalan[$constant - 1]['yt']) + $himpunanKeDua + ($constant - 1) - ($himpunan * $constant);

        $prediksiTiapMinggu = [];
        $sumRes = 0;
        for ($i = 0; $i < $constant; $i++) {
            $minggu = $data[$i]->minggu;
            $regresive = round($himpunanKeDua, 0, PHP_ROUND_HALF_UP);
            $y = $data[$i]->qty;
            $yAksen = $y > $regresive ? ($regresive + 1) : $regresive;
            $error = $y - $himpunanKeDua;
            $errorAbsolute = abs(round($error, 3));
            $res = $errorAbsolute / $y;
            $sumRes = $sumRes + $res;
            $temp = [
                'periode' => $minggu,
                'y' => $y,
                'y_aksen' => $yAksen,
                'error' => round($error, 3),
                'error_absolute' => $errorAbsolute,
                'result' => round($res, 7)
            ];
            array_unshift($prediksiTiapMinggu, $temp);
        }

        $mape = ((1 / $constant) * $sumRes) * 100;
        return [
            'peramalan' => $peramalan,
            'summary' => $summary,
            'himpunan' => $himpunan,
            'himpunanKe2' => $himpunanKeDua,
            'prediksi' => (int)$prediksi,
            'mape_data' => $prediksiTiapMinggu,
            'sum_mape_res' => $sumRes,
            'mape' => round($mape, 3)
        ];
    }
}
