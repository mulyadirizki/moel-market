<?php

use Illuminate\Support\Facades\Route;

// auth
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\RedirectController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Glob\KaryawanController;

// koffe backend
use App\Http\Controllers\Koffe\Backend\MainController;
use App\Http\Controllers\Koffe\Backend\KoffePenjualanController;
use App\Http\Controllers\Koffe\Backend\KoffePembelianController;
use App\Http\Controllers\Koffe\Backend\KoffePendapatanController;

// front koffea
use App\Http\Controllers\Koffe\Frontend\FrontController;
use App\Http\Controllers\Koffe\Frontend\CategoryController;
use App\Http\Controllers\Koffe\Frontend\ItemController;

// market backend
use App\Http\Controllers\Market\Backend\MarketMasterController;
use App\Http\Controllers\Market\Backend\MarketBarangController;
use App\Http\Controllers\Market\Backend\MarketPenerimaanBarangController;

// market frontend
use App\Http\Controllers\Market\Frontend\MarketFrontController;
use App\Http\Controllers\Market\Frontend\MarketPenjualanController;

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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::group(['middleware' => 'guest'], function() {
    // Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/', [LoginController::class, 'login'])->name('login');
    Route::post('/login-store', [LoginController::class, 'dologin'])->name('dologin');
    Route::get('/id/sign-up', [RegisterController::class, 'register'])->name('register');
    Route::post('/register', [RegisterController::class, 'doregister'])->name('doregister');
});

Route::group(['middleware' => ['auth', 'checkrole:1,2']], function() {
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('no-cache');
    Route::get('/redirect', [RedirectController::class, 'cek']);
});

// untuk admin
Route::group([ 'prefix' => 'backend', 'middleware' => ['auth', 'checkrole:1,3', 'no-cache']], function() {

    Route::group([ 'prefix' => 'global'], function() {
        Route::get('/data-karyawan', [KaryawanController::class, 'karyawan'])->name('karyawan.data');
        Route::post('/data-karyawan/add', [KaryawanController::class, 'karyawanAdd'])->name('karyawan.add');
    });
    Route::group([ 'prefix' => 'koffe'], function() {
        Route::get('/', [MainController::class, 'index'])->name('admin');

        Route::get('/data-penjualan/butuh-dibayarkan', [KoffePenjualanController::class, 'penjualanButuhDibayarkan'])->name('penjualan.butuh.dibayarkan');
        Route::get('/data-penjualan/selesai', [KoffePenjualanController::class, 'penjualanSelesai'])->name('penjualan.selesai');
        Route::get('/data-penjualan/refund', [KoffePenjualanController::class, 'penjualanRefund'])->name('penjualan.refund');

        Route::get('/data-pembelian', [KoffePembelianController::class, 'dataPembelian'])->name('data.pembelian');

        Route::get('/data-pendapatan', [KoffePendapatanController::class, 'dataPendapatan'])->name('data.pendapatan');
    });

    Route::group([ 'prefix' => 'market'], function() {
        Route::get('/', [HomeController::class, 'indexMarket'])->name('admin.market');

        // master dropdown
        Route::get('/master/get-data-kategori', [MarketMasterController::class, 'getKategori'])->name('get.kategori');
        Route::get('/master/get-data-satuan', [MarketMasterController::class, 'getSatuan'])->name('get.satuan');
        Route::get('/master/get-data-merek', [MarketMasterController::class, 'getMerek'])->name('get.merek');
        Route::get('/master/get-data-supplier', [MarketMasterController::class, 'getSupplier'])->name('get.supplier');
        Route::get('/master/get-data-barang', [MarketMasterController::class, 'getBarang'])->name('get.barang');

        Route::get('/data-satuan', [MarketMasterController::class, 'satuan'])->name('data.satuan');
        Route::post('/data-satuan/add', [MarketMasterController::class, 'satuanAdd'])->name('data.satuan.add');
        Route::get('/data-satuan/edit/{id}', [MarketMasterController::class, 'satuanEdit'])->name('data.satuan.edit');
        Route::delete('/data-satuan/delete/{id}', [MarketMasterController::class, 'satuanDelete'])->name('data.satuan.delete');

        Route::get('/data-kategori', [MarketMasterController::class, 'kategori'])->name('data.kategori');
        Route::post('/data-kategori/add', [MarketMasterController::class, 'kategoriAdd'])->name('data.kategori.add');
        Route::get('/data-kategori/edit/{id}', [MarketMasterController::class, 'kategoriEdit'])->name('data.kategori.edit');
        Route::delete('/data-kategori/delete/{id}', [MarketMasterController::class, 'kategoriDelete'])->name('data.kategori.delete');

        Route::get('/data-supplier', [MarketMasterController::class, 'supplier'])->name('data.supplier');
        Route::post('/data-supplier/add', [MarketMasterController::class, 'supplierAdd'])->name('data.supplier.add');
        Route::get('/data-supplier/edit/{id}', [MarketMasterController::class, 'supplierEdit'])->name('data.supplier.edit');
        Route::delete('/data-supplier/delete/{id}', [MarketMasterController::class, 'supplierDelete'])->name('data.supplier.delete');

        // Data barang
        Route::get('/data-barang', [MarketBarangController::class, 'barang'])->name('data.barang');
        Route::post('/data-barang/add', [MarketBarangController::class, 'barangAdd'])->name('data.barang.add');
        Route::get('/data-barang/edit/{id}', [MarketBarangController::class, 'barangEdit'])->name('data.barang.edit');
        Route::post('/data-barang/delete/{id}', [MarketBarangController::class, 'barangDelete'])->name('data.barang.delete');

        // Data barang terima
        Route::get('/data-barang-masuk', [MarketPenerimaanBarangController::class, 'barangMasuk'])->name('data.barang.masuk');
        Route::post('/data-barang-masuk/add', [MarketPenerimaanBarangController::class, 'barangMasukAdd'])->name('data.barang.masuk.add');
        Route::get('/data-barang-masuk/edit/{id}', [MarketPenerimaanBarangController::class, 'barangMasukEdit'])->name('data.barang.masuk.edit');
        Route::post('/data-barang-masuk/update', [MarketPenerimaanBarangController::class, 'barangMasukUpdate'])->name('data.barang.masuk.update');
        Route::post('/data-barang-masuk/delete/{id}', [MarketPenerimaanBarangController::class, 'barangMasukDelete'])->name('data.barang.masuk.delete');

        Route::get('/data-stok-barang', [MarketBarangController::class, 'stokBarang'])->name('data.stok.barang');
    });

    Route::group([ 'prefix' => 'market/manajemen'], function() {
        Route::get('/', [HomeController::class, 'indexManajemenMarket'])->name('market.manajemen');

        Route::get('/data-penjualan', [HomeController::class, 'dataPenjualan'])->name('manajemen.penjualan');
        Route::get('/data-stok-barang', [HomeController::class, 'dataStokBarang'])->name('manajemen.stok.barang');
        Route::get('/data-laba-pendapatan', [HomeController::class, 'dataLabaPendapatan'])->name('manajemen.laba.pendapatan');
    });
});

Route::group([ 'prefix' => 'front', 'middleware' => ['auth', 'checkrole:2']], function() {
    Route::group([ 'prefix' => 'koffe'], function() {
        Route::get('/', [FrontController::class, 'index'])->name('kasir');

        Route::get('/create-item', [ItemController::class, 'createItem'])->name('create.item');
        Route::post('/create-item/add', [ItemController::class, 'createItemAdd'])->name('create.item.add');

        Route::get('/manage-category', [CategoryController::class, 'manageCategory'])->name('manage.category');
        Route::post('/manage-category/add', [CategoryController::class, 'manageCategoryAdd'])->name('manage.category.add');
        Route::delete('/manage-category/delete/{id}', [CategoryController::class, 'manageCategoryDelete'])->name('manage.category.delete');

        Route::get('/all-item', [FrontController::class, 'allItem'])->name('all.item');
        Route::get('/item-category/{id}', [FrontController::class, 'categoryItem'])->name('category.item');
        Route::get('/item-variant/{id}', [FrontController::class, 'variantItem'])->name('varian.item');

        Route::get('/payment-order', [FrontController::class, 'paymentOrder'])->name('payment.order');
        Route::post('/payment-order/add', [FrontController::class, 'paymentOrderAdd'])->name('payment.order.add');

        Route::get('/settings', [FrontController::class, 'setting'])->name('setting');

        Route::get('/billing-print/{id}', [FrontController::class, 'billingPrint'])->name('billing.print');
        Route::get('/billing-print/day/{id}', [FrontController::class, 'billingPrintHarian'])->name('billing.print.harian');

        Route::get('/activity', [FrontController::class, 'activity'])->name('activity');
        Route::get('/activity-detail/{id}', [FrontController::class, 'activityDetail'])->name('activity.detail');
        Route::post('/payment-method/change', [FrontController::class, 'changePaymentMethod'])->name('change.payment.method');
        Route::post('/transaksi-penjualan/delete', [FrontController::class, 'transaksiPenjualanDelete'])->name('transaksi.penjualan.delete');

        // pengeluaran
        Route::get('/pengeluaran', [FrontController::class, 'pengeluaranIndex'])->name('pengeluaran');
        Route::post('/pengeluaran/add', [FrontController::class, 'pengeluaranAdd'])->name('pengeluaran.add');
        Route::get('/pengeluaran/get-id/{id}', [FrontController::class, 'pengeluaranGetById'])->name('pengeluaran.getById');
        Route::post('/pengeluaran/delete/{id}', [FrontController::class, 'pengeluaranDelete'])->name('pengeluaran.delete');
    });

    Route::group([ 'prefix' => 'market'], function() {
        Route::get('/', [MarketFrontController::class, 'index'])->name('market.kasir');

        Route::get('/get-data-barang', [MarketFrontController::class, 'getDataBarang'])->name('get.data.barang');
        Route::get('/get-data-barang-id/{id}', [MarketFrontController::class, 'getDataBarangById'])->name('get.data.barangId');

        Route::post('/store/transaksi-penjualan', [MarketPenjualanController::class, 'saveTransaksi'])->name('save.transaksi');

        Route::get('/history-transaksi', [MarketPenjualanController::class, 'historyTransaksi'])->name('history.transaksi');
        Route::post('/history-transaksi/delete', [MarketPenjualanController::class, 'historyTransaksiDelete'])->name('history.transaksi.delete');

        Route::get('/transaksi-print/{id}', [MarketPenjualanController::class, 'transaksiPrint'])->name('market.print.transaksi');
    });
});

