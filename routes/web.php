<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
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

Route::get('/admin', function () {
    return view('home');
});

Route::get('/login', function () {
    return view('login');
});


Route::get('/user', function () {
    return view('user/dashboard');
});

Route::get('/admin', function () {
    return view('admin/dashboard');
});

Route::get('/admin/barang', function () {
    return view('admin/barang/barang');
});

Route::get('/admin/penjualan', function () {
    return view('admin/penjualan/penjualan');
});

Route::get('/admin/laporan', function () {
    return view('admin/laporan/laporan');
});

Route::get('/admin/barangmasuk', function () {
    return view('admin/barangmasuk/barangmasuk');
});

Route::get('/admin/admin', function () {
    return view('admin/admin/admin');
});
