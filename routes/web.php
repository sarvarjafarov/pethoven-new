<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\CartController;

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

// Checkout Routes (placeholders - will be replaced with controllers)
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', function () {
        return response('Checkout - Coming soon');
    })->name('index');
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

// Account Routes (placeholders - will require auth middleware)
Route::prefix('account')->name('account.')->group(function () {
    Route::get('/dashboard', function () {
        return response('Dashboard - Coming soon');
    })->name('dashboard');
});

// Placeholder auth routes (will be replaced with Laravel Breeze)
Route::get('/login', function () {
    return response('Login - Coming soon');
})->name('login');

Route::get('/register', function () {
    return response('Register - Coming soon');
})->name('register');

Route::post('/logout', function () {
    return redirect()->route('home');
})->name('logout');
