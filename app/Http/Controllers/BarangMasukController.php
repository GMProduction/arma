<?php


namespace App\Http\Controllers;


use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\Prediksi;
use Illuminate\Http\Request;

class BarangMasukController
{
    public function index()
    {
        $data = Barang::all();
        $dataPrediksi = Prediksi::with('barang')->get();
        return view('admin/barangmasuk/barangmasuk')->with(['data' => $data, 'prediksi' => $dataPrediksi]);
    }

    public function getMingguKe(Request $request)
    {
        try {
            $idBarang = $request->query->get("id");
            $data = Penjualan::where('barang_id', '=', $idBarang)->orderBy('minggu', 'DESC')->first();
            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json('Error ' . $e, 500);
        }
    }


    public function prediksi(Request $request)
    {
        try {
            $idBarang = $request->request->get("id");
            $constant = 12;
            $data = Penjualan::where('barang_id', '=', $idBarang)->orderBy('minggu', 'DESC')->take($constant)->get();
            if (count($data) < $constant) {
                return response()->json(['msg' => 'Data Tidak Bisa Di Perdiksi Karena Belum Memenuhi Nilai Konstan ' . $constant, 'code' => 202], 200);
            }

            $lastPrediksi = Prediksi::where('barang_id', '=', $idBarang)->orderBy('minggu', 'DESC')->first();
            if ($lastPrediksi) {
                $mingguTerakhirPrediksi = $lastPrediksi->minggu;
                $mingguTerakhirPenjualan = $data[0]->minggu + 1;
                if ($mingguTerakhirPrediksi === $mingguTerakhirPenjualan) {
                    return response()->json(['msg' => 'Data Minggu Ke ' . $data[0]->minggu . ' Sudah Di Prediksi', 'code' => 202], 200);
                }
            }
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

            $tbPrediksi = new Prediksi();
            $tbPrediksi->minggu = $data[0]->minggu + 1;
            $tbPrediksi->barang_id = $idBarang;
            $tbPrediksi->prediksi = (int)$prediksi;
            $tbPrediksi->kesalahan = (int)$mape;
            $tbPrediksi->masuk = 0;
            $tbPrediksi->penjualan_id = $data[0]->id;
            $tbPrediksi->save();
            return response()->json([
                'msg' => 'Berhasil Menyimpan Prediksi',
                'code' => 200
            ], 200);
        } catch (\Exception $e) {
            return response()->json('Error ' . $e, 500);
        }

//        return [
//            'peramalan' => $peramalan,
//            'summary' => $summary,
//            'himpunan' => $himpunan,
//            'himpunanKe2'=> $himpunanKeDua,
//            'prediksi' => (int) $prediksi,
//            'mape_data' => $prediksiTiapMinggu,
//            'sum_mape_res' => $sumRes ,
//            'mape' => round($mape, 3)
//        ];

    }

    public function tambahStok(Request $request)
    {
        try {
            $idBarang = $request->request->get('id_barang');
            $qty = $request->request->get('qty');
            $idPredict = $request->request->get('id_predict');
            /** @var Prediksi $prediksi */
            $prediksi = Prediksi::find($idPredict);
            $prediksi->masuk = $qty;
            $prediksi->save();
            /** @var Barang $barang */
            $barang = Barang::find($idBarang);
            $currentQty = $barang->qty;
            $barang->qty = $currentQty + $qty;
            $barang->save();
            return response()->json('Success', 200);
        } catch (\Exception $e) {
            return response()->json('Error ' . $e, 500);
        }
    }

    public function editStok(Request $request)
    {
        try {
            $idBarang = $request->request->get('id_barang');
            $qty = $request->request->get('qty');
            $idPredict = $request->request->get('id_predict');
            /** @var Prediksi $prediksi */
            $prediksi = Prediksi::find($idPredict);
            $curretQtyPredict = $prediksi->masuk;
            $prediksi->masuk = $qty;
            $prediksi->save();
            /** @var Barang $barang */
            $barang = Barang::find($idBarang);
            $currentQty = $barang->qty;
            $barang->qty = ($currentQty - $curretQtyPredict) + $qty;
            $barang->save();
            return response()->json('Success', 200);
        } catch (\Exception $e) {
            return response()->json('Error ' . $e, 500);
        }
    }

    public function hapusStok($id)
    {
        try {
            /** @var Prediksi $prediksi */
            $prediksi = Prediksi::find($id);
            $curretQtyPredict = $prediksi->masuk;
            Prediksi::destroy($id);
            /** @var Barang $barang */
            $barang = Barang::find($prediksi->barang_id);
            $currentQty = $barang->qty;
            $barang->qty = ($currentQty - $curretQtyPredict);
            $barang->save();
            return response()->json('Success', 200);
        } catch (\Exception $e) {
            return response()->json('Error ' . $e, 500);
        }
    }
}
