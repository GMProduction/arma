<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\PenjualanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [AuthController::class, 'showFormLogin']);
Route::post("/login", [AuthController::class, 'login']);
Route::get("/logout", [AuthController::class, 'logout']);



Route::get('/admin', function () {
    return view('admin/dashboard');
});

Route::get('/admin/barang', [BarangController::class, 'index']);
Route::post('/admin/barang/tambah', [BarangController::class, 'add']);
Route::post('/admin/barang/edit', [BarangController::class, 'edit']);
Route::post('/admin/barang/hapus/{id}', [BarangController::class, 'destroy']);

Route::get('/admin/penjualan', [PenjualanController::class, 'index']);
Route::get('/admin/hitung', [PenjualanController::class, 'hitung']);

Route::get('/admin/laporan', function () {
    return view('admin/laporan/laporan');
});

Route::get('/admin/barangmasuk', [BarangMasukController::class, 'index']);
Route::get('/admin/barangmasuk/minggu', [BarangMasukController::class, 'getMingguKe']);
Route::post('/admin/barangmasuk/prediksi', [BarangMasukController::class, 'prediksi']);
Route::post('/admin/barangmasuk/tambah', [BarangMasukController::class, 'tambahStok']);
Route::post('/admin/barangmasuk/edit', [BarangMasukController::class, 'editStok']);
Route::post('/admin/barangmasuk/hapus/{id}', [BarangMasukController::class, 'hapusStok']);


Route::get('/admin/admin', function () {
    return view('admin/admin/admin');
});
