<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\AccountController;

// Homepage
Route::get('/', function () {
    return view('frontend.pages.home');
})->name('home');

// Static Pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');

// Shop Routes
Route::prefix('shop')->name('shop.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');
});

// Cart Routes
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::put('/{lineId}', [CartController::class, 'update'])->name('update');
    Route::delete('/{lineId}', [CartController::class, 'remove'])->name('remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
    Route::get('/count', [CartController::class, 'count'])->name('count');
});

// Wishlist Route (placeholder)
Route::get('/wishlist', function () {
    return response('Wishlist - Coming soon');
})->name('wishlist.index');

// Checkout Routes
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/process', [CheckoutController::class, 'process'])->name('process');
    Route::get('/payment', [CheckoutController::class, 'payment'])->name('payment');
    Route::post('/process-payment', [CheckoutController::class, 'processPayment'])->name('process-payment');
    Route::get('/success/{order}', [CheckoutController::class, 'success'])->name('success');
});

// Account Routes (protected)
Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::get('/dashboard', [AccountController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [AccountController::class, 'orderShow'])->name('orders.show');
    Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
    Route::post('/profile', [AccountController::class, 'profileUpdate'])->name('profile.update');
});

// Blog Routes (placeholders - will be replaced with controllers)
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', function () {
        return response('Blog - Coming soon');
    })->name('index');

    Route::get('/{slug}', function ($slug) {
        return response('Blog post - Coming soon');
    })->name('show');
});

// Include Breeze auth routes
require __DIR__.'/auth.php';
