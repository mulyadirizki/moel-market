<?php

use Illuminate\Support\Facades\Route;

// auth
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\Backend\KasirController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\HomeController;

// koffe backend
use App\Http\Controllers\Koffe\Backend\MainController;
use App\Http\Controllers\Koffe\Backend\KaryawanController;
use App\Http\Controllers\Koffe\Backend\ActivityController;

// front
use App\Http\Controllers\Koffe\Frontend\FrontController;
use App\Http\Controllers\Koffe\Frontend\CategoryController;
use App\Http\Controllers\Koffe\Frontend\ItemController;
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
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/redirect', [RedirectController::class, 'cek']);
});

// untuk superadmin
Route::group([ 'prefix' => 'admin/koffe', 'middleware' => ['auth', 'checkrole:1']], function() {
    Route::get('/', [MainController::class, 'index'])->name('admin');

    Route::get('/data-karyawan', [KaryawanController::class, 'karyawan'])->name('karyawan.data');
    Route::post('/data-karyawan/add', [KaryawanController::class, 'karyawanAdd'])->name('karyawan.add');

    Route::get('/data-activity/refund', [ActivityController::class, 'activityRefund'])->name('activity.refund');
});

Route::group([ 'prefix' => 'front/koffe', 'middleware' => ['auth', 'checkrole:2']], function() {
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
});


