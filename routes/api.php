<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\HistoyController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\SettlementController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/neworder', [OrderController::class, 'checkOrder'])->name('newOrder');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    //PROFIL
    Route::get('/profil', [ProfilController::class, 'vprofil']);
    Route::get('/profil/{id}', [ProfilController::class, 'show']);
    //CATEGORY
    Route::get('/category', [CategoryController::class, 'index']);
    //MENU
    Route::get('/product', [ProductController::class, 'index']);
    Route::get('/product/{id}', [ProductController::class, 'show']);
    //DICSOUNT
    Route::get('/discounts', [DiscountController::class, 'vdiscount']);
    //CART
    Route::get('/cart', [CartController::class, 'getCart']);
    Route::post('/cart-menus/{cartId}', [CartController::class, 'store']);
    Route::get('/carts', [CartController::class, 'vcart']);
    Route::get('/carts/{id}', [CartController::class, 'show']);
    Route::delete('/carts/{cartId}/cart-menus/{cartMenuId}', [CartController::class, 'rmcart']);
    //ORDER
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/orders/{cartId}', [OrderController::class, 'placeOrder']);
    Route::post('/orders/cash/{cartId}', [OrderController::class, 'placeCash']);
    //ARCHIVE
    Route::post('/order/{orderId}/archive', [OrderController::class, 'archive']);
    //HISTORY
    Route::get('/historys', [Histoycontroller::class, 'index']);
    //SETTLEMENT
    Route::get('/settlements', [SettlementController::class, 'index']);
    Route::get('/settlement/{id}', [SettlementController::class, 'show']);
    Route::post('/createstart', [SettlementController::class, 'poststart']);
    Route::post('/createtotal', [SettlementController::class, 'posttotal']);
    //LOGOUT
    Route::post('/logout', [AuthController::class, 'logout']);
});
