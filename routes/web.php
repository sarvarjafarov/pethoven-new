<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\AccountController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\NewsletterController;
use App\Http\Controllers\Frontend\QuickViewController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Frontend\CompareController;

// Homepage
Route::get('/', function () {
    $featuredProducts = \Lunar\Models\Product::with([
        'variants.prices',
        'thumbnail',
        'collections',
        'defaultUrl'
    ])
    ->where('status', 'published')
    ->latest()
    ->take(8)
    ->get();

    return view('frontend.pages.home', compact('featuredProducts'));
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

// Products Routes (alias for shop)
Route::prefix('products')->name('products.')->group(function () {
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

// Wishlist Routes
Route::prefix('wishlist')->name('wishlist.')->group(function () {
    Route::get('/', [WishlistController::class, 'index'])->name('index');
    Route::post('/add', [WishlistController::class, 'add'])->name('add');
    Route::delete('/{productId}', [WishlistController::class, 'remove'])->name('remove');
    Route::post('/clear', [WishlistController::class, 'clear'])->name('clear');
    Route::get('/count', [WishlistController::class, 'count'])->name('count');
});

// Compare Routes
Route::prefix('compare')->name('compare.')->group(function () {
    Route::get('/', [CompareController::class, 'index'])->name('index');
    Route::post('/add', [CompareController::class, 'add'])->name('add');
    Route::delete('/{productId}', [CompareController::class, 'remove'])->name('remove');
    Route::post('/clear', [CompareController::class, 'clear'])->name('clear');
    Route::get('/count', [CompareController::class, 'count'])->name('count');
});

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

// Blog Routes
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
});

// Newsletter Routes
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{email}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// Quick View Route
Route::get('/product/quick-view/{slug}', [QuickViewController::class, 'show'])->name('product.quickview');

// Include Breeze auth routes
require __DIR__.'/auth.php';
