<?php

use Illuminate\Support\Facades\Route;

// Homepage
Route::get('/', function () {
    return view('frontend.pages.home');
})->name('home');

// Static Pages (placeholders for now)
Route::get('/about', function () {
    return view('frontend.pages.about');
})->name('about');

Route::get('/contact', function () {
    return view('frontend.pages.contact');
})->name('contact');

Route::get('/faq', function () {
    return view('frontend.pages.faq');
})->name('faq');

// Shop Routes (placeholders - will be replaced with controllers)
Route::prefix('shop')->name('shop.')->group(function () {
    Route::get('/', function () {
        return response('Shop page - Coming soon');
    })->name('index');

    Route::get('/product/{slug}', function ($slug) {
        return response('Product details - Coming soon');
    })->name('product.show');
});

// Cart Routes (placeholders - will be replaced with controllers)
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', function () {
        return response('Cart - Coming soon');
    })->name('index');
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
