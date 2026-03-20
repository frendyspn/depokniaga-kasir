<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\ProdukController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', 'App\Http\Controllers\HomeController@index');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/proses_login', [LoginController::class, 'prosesLogin'])->name('proses_login');
Route::get('/otp', [LoginController::class, 'otpLogin'])->name('otp');
Route::post('/proses_otp', [LoginController::class, 'prosesOtp'])->name('proses_otp');

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/cabang', [CabangController::class, 'index'])->name('cabang');
Route::get('/cabang_tambah', [CabangController::class, 'AddData'])->name('cabang_tambah');
Route::get('/cabang_edit/{id}', [CabangController::class, 'EditData'])->name('cabang_edit');
Route::post('/cabang_simpan', [CabangController::class, 'SaveData'])->name('cabang_simpan');
Route::get('/cabang_hapus/{id}', [CabangController::class, 'DeleteData'])->name('cabang_hapus');

Route::get('/no_store', [CabangController::class, 'NoStore'])->name('no_store');
Route::get('/store_register', [CabangController::class, 'AddStore'])->name('store_register');

Route::post('/get_provinsi', [Controller::class, 'GetProvinsi'])->name('get_provinsi');
Route::post('/get_city', [Controller::class, 'GetKota'])->name('get_city');
Route::post('/get_district', [Controller::class, 'GetKecamatan'])->name('get_district');

Route::get('/user', [UserController::class, 'index'])->name('user');
Route::get('/user_tambah', [UserController::class, 'AddData'])->name('user_tambah');
Route::post('/user_simpan', [UserController::class, 'SaveData'])->name('user_simpan');
Route::get('/user_edit/{id}', [UserController::class, 'EditData'])->name('user_edit');
Route::get('/user_hapus/{id}', [UserController::class, 'DeleteData'])->name('user_hapus');

Route::get('/pos', [PosController::class, 'index'])->name('pos');
Route::post('/pos_add_keranjang', [PosController::class, 'AddKeranjang'])->name('pos_add_keranjang');
Route::get('/pos_view_keranjang', [PosController::class, 'ViewKeranjang'])->name('pos_view_keranjang');
Route::post('/pos_pilih_pengiriman', [PosController::class, 'PilihPengiriman'])->name('pos_pilih_pengiriman');
Route::post('/pos_input_diskon', [PosController::class, 'InputDiskon'])->name('pos_input_diskon');
Route::post('/pos_input_bayar', [PosController::class, 'InputBayar'])->name('pos_input_bayar');
Route::post('/pos_edit_barang_keranjang', [PosController::class, 'EditBarangKeranjang'])->name('pos_edit_barang_keranjang');
Route::post('/pos_update_barang_keranjang', [PosController::class, 'UpdateBarangKeranjang'])->name('pos_update_barang_keranjang');
Route::post('/pos_cek_konsumen', [PosController::class, 'CekKonsumen'])->name('pos_cek_konsumen');
Route::post('/pos_cek_kupon', [PosController::class, 'CekKupon'])->name('pos_cek_kupon');
Route::post('/pos_bayar', [PosController::class, 'PosBayar'])->name('pos_bayar');
Route::get('/pos_finish', [PosController::class, 'PosFinish'])->name('pos_finish');
Route::post('/pos_scan_barcode', [PosController::class, 'ScanBarcode'])->name('pos_scan_barcode');
Route::post('/pos_list_barang', [PosController::class, 'ViewListBarang'])->name('pos_list_barang');

Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi');
Route::get('/transaksi_lihat_semua', [TransaksiController::class, 'semuaTransaksi'])->name('transaksi_lihat_semua');
Route::get('/transaksi/detail/{aplikasi}/{layanan}/{id}', [TransaksiController::class, 'getTransaksi'])->name('transaksi/detail');
Route::get('/transaksi/confirm/{id}/{status}', [TransaksiController::class, 'confirmOrder'])->name('transaksi/confirm');
Route::post('/transaksi/cancel_transaksi', [TransaksiController::class, 'cancelOrder'])->name('transaksi/cancel_transaksi');

Route::get('/produk', [ProdukController::class, 'index'])->name('produk');
Route::post('/search_produk', [ProdukController::class, 'searchProduk'])->name('search_produk');
Route::get('/add_produk', [ProdukController::class, 'addProduk'])->name('add_produk');
Route::post('/get_sub_kategori', [ProdukController::class, 'getSubKategoriProduk'])->name('get_sub_kategori');
Route::post('/save_produk', [ProdukController::class, 'saveProduk'])->name('save_produk');
Route::get('/edit_produk/{id}', [ProdukController::class, 'editProduk'])->name('edit_produk');
Route::post('/update_produk', [ProdukController::class, 'updateProduk'])->name('update_produk');

Route::get('/test', [Controller::class, 'api_token']);


Route::get('/change/{locale}', function (string $locale) {
    session()->put('locale', $locale);
 
    return redirect('home');

});

