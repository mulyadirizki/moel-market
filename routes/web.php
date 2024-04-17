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
    Route::post('/', [LoginController::class, 'dologin'])->name('dologin');
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
});

Route::group([ 'prefix' => 'front/koffe', 'middleware' => ['auth', 'checkrole:2']], function() {
    Route::get('/', [FrontController::class, 'index'])->name('kasir');

    Route::get('/item-category/{id}', [FrontController::class, 'categoryItem'])->name('category.item');
    Route::get('/item-variant/{id}', [FrontController::class, 'variantItem'])->name('varian.item');

    Route::get('/create-item', [ItemController::class, 'createItem'])->name('create.item');
    Route::post('/create-item/add', [ItemController::class, 'createItemAdd'])->name('create.item.add');

    Route::get('/manage-category', [CategoryController::class, 'manageCategory'])->name('manage.category');
    Route::post('/manage-category/add', [CategoryController::class, 'manageCategoryAdd'])->name('manage.category.add');
    Route::delete('/manage-category/delete/{id}', [CategoryController::class, 'manageCategoryDelete'])->name('manage.category.delete');
});
// Route::group(['middleware' => 'guest'], function() {
//     Route::get('/', [LoginController::class, 'pageLogin'])->name('pageLogin');
//     Route::get('/login', [LoginController::class, 'pageLogin'])->name('pageLogin');
//     Route::post('/', [AuthController::class, 'dologin']);
//     Route::post('/login-proses', [LoginController::class, 'loginStore'])->name('loginStore');

// });

// // untuk superadmin dan pegawai
// Route::group(['middleware' => ['auth', 'checkrole:1,2']], function() {
//     Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
//     Route::get('/redirect', [RedirectController::class, 'cek']);
// });

// Route::group(['middleware' => ['auth', 'checkrole:2']], function() {
//     Route::get('/kasir', [KasirController::class, 'index'])->name('kasir');
// });


