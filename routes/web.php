<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\CartCheckoutController;
use App\Http\Controllers\SellerDashboardController;
use App\Http\Controllers\BuyerDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes - Plazio Hybrid Multi-Seller Marketplace
|--------------------------------------------------------------------------
*/

// Marketplace Routes (Organic Catalog & Standalone Storefront)
Route::get('/', [MarketplaceController::class, 'index'])->name('marketplace.index');
Route::get('/p/{slug}', [MarketplaceController::class, 'showProduct'])->name('marketplace.product');
Route::get('/toko/{slug}', [MarketplaceController::class, 'showStore'])->name('marketplace.store');

// Cart & Checkout Routes
Route::get('/cart', [CartCheckoutController::class, 'viewCart'])->name('cart.index');
Route::post('/cart/add', [CartCheckoutController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [CartCheckoutController::class, 'updateCart'])->name('cart.update');
Route::get('/cart/remove/{productId}', [CartCheckoutController::class, 'removeFromCart'])->name('cart.remove');

Route::get('/checkout', [CartCheckoutController::class, 'checkout'])->name('checkout.index');
Route::post('/checkout/process', [CartCheckoutController::class, 'processCheckout'])->name('checkout.process');

// Buyer Dashboard Routes
Route::get('/buyer/orders', [BuyerDashboardController::class, 'orders'])->name('buyer.orders');
Route::post('/buyer/orders/{id}/confirm', [BuyerDashboardController::class, 'confirmReceived'])->name('buyer.orders.confirm');
Route::post('/buyer/orders/{id}/review', [BuyerDashboardController::class, 'storeReview'])->name('buyer.orders.review');
Route::post('/buyer/orders/{id}/dispute', [BuyerDashboardController::class, 'storeDispute'])->name('buyer.orders.dispute');

// Seller Dashboard Routes
Route::prefix('seller')->name('seller.')->group(function () {
    Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');
    
    // USP Feature: Tracking Link Generator & Analytics
    Route::get('/tracking-links', [SellerDashboardController::class, 'trackingLinks'])->name('tracking-links');
    Route::post('/tracking-links', [SellerDashboardController::class, 'storeTrackingLink'])->name('tracking-links.store');
    
    // Products & Store
    Route::get('/products', [SellerDashboardController::class, 'products'])->name('products');
    Route::post('/products', [SellerDashboardController::class, 'storeProduct'])->name('products.store');

    // Orders & Payouts
    Route::get('/orders', [SellerDashboardController::class, 'orders'])->name('orders');
    Route::post('/orders/{id}/update', [SellerDashboardController::class, 'updateOrderStatus'])->name('orders.update');

    Route::get('/payouts', [SellerDashboardController::class, 'payouts'])->name('payouts');
    Route::post('/payouts', [SellerDashboardController::class, 'requestPayout'])->name('payouts.request');
});

// Admin Panel Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/disputes/{id}/resolve', [AdminDashboardController::class, 'resolveDispute'])->name('disputes.resolve');
});

// Quick Auth & Demo Switch Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/switch-role/{role}', [AuthController::class, 'switchRole'])->name('auth.switch-role');
