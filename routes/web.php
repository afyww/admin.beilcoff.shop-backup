<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QrController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Pagescontroller;
use App\Http\Controllers\HistoyController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\SettlementController;

//AUTH CONTROLLER
Route::get('/', [AuthController::class, 'vlogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('masuk');
//QR CONTROLLER
Route::get('/adminqr/{id}', [QrController::class, 'AdminQr'])->name('admin-qr');
Route::get('/userqr/{id}', [QrController::class, 'UserQr'])->name('user-qr');

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

Route::middleware('auth:sanctum')->group(function () {

    //AUTH CONTROLLER
    Route::post('/createchair', [AuthController::class, 'registerchair'])->name('registerchair');
    Route::post('/createuser', [AuthController::class, 'register'])->name('registeruser');
    Route::post('/logout', [AuthController::class, 'logout'])->name('keluar');
    //PAGES CONTROLLER
    Route::get('/dashboard', [Pagescontroller::class, 'vdashboard'])->name('dashboard');
    Route::get('/search', [PagesController::class, 'vsearch'])->name('search');
    Route::get('/prediksi', [Pagescontroller::class, 'vprediksi'])->name('prediksi');
    //USER CONTROLLER
    Route::get('/chair', [UserController::class, 'vchair'])->name('chair');
    Route::get('/users', [UserController::class, 'vuser'])->name('user');
    Route::get('/addchair', [UserController::class, 'vcreatechair'])->name('addchair');
    Route::get('/createuser', [UserController::class, 'vcreateuser'])->name('adduser');
    Route::delete('/users/{id}/delete', [UserController::class, 'rmchair'])->name('delchair');
    Route::delete('/rmuser/{id}/delete', [UserController::class, 'rmuser'])->name('deluser');
    //ORDER CONTROLLER
    Route::get('/order', [OrderController::class, 'index'])->name('order');
    Route::get('/createorder', [OrderController::class, 'create'])->name('addorder');
    Route::post('/cashpayment', [OrderController::class, 'cashpayment'])->name('cashpayment');
    Route::post('/postorder', [OrderController::class, 'adminOrder'])->name('postorder');
    Route::post('/order/{orderId}/archive', [OrderController::class, 'archive'])->name('archive');
    Route::delete('/order/{id}/delete', [OrderController::class, 'destroy'])->name('delorder');
    //PROFIL CONTROLLER
    Route::get('/profil', [ProfilController::class, 'vprofil'])->name('profil');
    Route::get('/profil/{id}', [ProfilController::class, 'veditprofil'])->name('editprofil');
    Route::put('/profil/{id}/update', [ProfilController::class, 'updateprofil'])->name('updateprofil');
    //MENU CONTROLLER
    Route::get('/product', [ProductController::class, 'index'])->name('product');
    Route::get('/createproduct', [ProductController::class, 'create'])->name('addproduct');
    Route::post('/postproduct', [ProductController::class, 'store'])->name('postproduct');
    Route::get('/editproduct/{id}', [ProductController::class, 'edit'])->name('editproduct');
    Route::get('/product/{id}/show', [ProductController::class, 'show'])->name('showproduct');
    Route::put('/product/{id}/update', [ProductController::class, 'update'])->name('updateproduct');
    Route::delete('/product/{id}/delete', [ProductController::class, 'destroy'])->name('delproduct');
    //CATEGORY CONTROLLER
    Route::get('/category', [CategoryController::class, 'index'])->name('category');
    Route::get('/addcategory', [CategoryController::class, 'create'])->name('addcategory');
    Route::post('/postcategory', [CategoryController::class, 'store'])->name('postcategory');
    Route::get('/editcategory/{id}', [CategoryController::class, 'edit'])->name('editcategory');
    Route::put('/category/{id}/update', [CategoryController::class, 'update'])->name('updatecategory');
    Route::delete('/category/{id}/delete', [CategoryController::class, 'destroy'])->name('delcategory');
    //HISTORY CONTROLLER
    Route::get('/history', [Histoycontroller::class, 'index'])->name('history');
    Route::get('/export-orders', [HistoyController::class, 'exportOrders'])->name('exportOrders');
    //CART  CONTROLLER
    Route::get('/addcart', [Cartcontroller::class, 'vaddcart'])->name('addcart');
    Route::post('/postcart', [CartController::class, 'postcart'])->name('postcart');
    Route::delete('/rmcart/{cartMenuId}', [CartController::class, 'removecart'])->name('removecart');
    //DISCOUNT CONTROLLER
    Route::get('/discount', [DiscountController::class, 'index'])->name('discount');
    Route::get('/adddiscount', [DiscountController::class, 'create'])->name('adddiscount');
    Route::post('/postdiscount', [DiscountController::class, 'store'])->name('postdiscount');
    Route::get('/editdiscounts/{id}', [DiscountController::class, 'edit'])->name('editdiscount');
    Route::put('/discounts/{id}/update', [DiscountController::class, 'update'])->name('updatediscount');
    Route::delete('discounts/{id}/delete', [DiscountController::class, 'destroy'])->name('deldiscount');
    //EXPENSE CONTROLLER
    Route::get('/expense', [ExpenseController::class, 'index'])->name('expense');
    Route::get('/addexpense', [ExpenseController::class, 'create'])->name('addexpense');
    Route::post('/postexpense', [ExpenseController::class, 'store'])->name('postexpense');
    Route::get('/editexpense/{id}', [ExpenseController::class, 'edit'])->name('editexpense');
    Route::put('/expense/{id}/update', [ExpenseController::class, 'update'])->name('updateexpense');
    Route::delete('expense/{id}/delete', [ExpenseController::class, 'destroy'])->name('delexpense');
    //SETTLEMENT CONTROLLER
    Route::get('/settlement', [SettlementController::class, 'index'])->name('settlement');
    Route::get('/settlement/{id}/show', [SettlementController::class, 'show'])->name('showsettlement');
    Route::delete('/settlement/{id}/delete', [SettlementController::class, 'destroy'])->name('delsettlement');
    Route::get('/addstartamount', [SettlementController::class, 'vstartamount'])->name('addstartamount');
    Route::get('/addtotalamount', [SettlementController::class, 'vtotalamount'])->name('addtotalamount');
    Route::post('/createstart', [SettlementController::class, 'poststart'])->name('poststart');
    Route::post('/createtotal', [SettlementController::class, 'posttotal'])->name('posttotal');
});
