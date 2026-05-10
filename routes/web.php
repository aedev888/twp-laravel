<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TelegramAuthController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PasswordResetController;

// ─── Public / SEO ────────────────────────────────────────────────────────────

Route::get('/sitemap.xml', [SitemapController::class, 'index']);
Route::get('/robots.txt', [SitemapController::class, 'robots']);

// ─── Marketplace homepage ─────────────────────────────────────────────────────

Route::get('/', [ProductController::class, 'index'])->name('home');

Route::get('/category/{taxonomy:slug}', [\App\Http\Controllers\CategoryController::class, 'show'])->name('categories.show');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/api/search', [ProductController::class, 'search'])->name('api.products.search');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/reviews', [\App\Http\Controllers\ReviewController::class, 'index'])->name('reviews.index');

// ─── Blog ────────────────────────────────────────────────────────────────────

Route::get('/blog', [\App\Http\Controllers\PostController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [\App\Http\Controllers\PostController::class, 'show'])->name('blog.show');

// ─── Authentication ───────────────────────────────────────────────────────────

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Telegram Login Widget callback (no CSRF — Telegram sends a GET redirect)
    Route::get('/auth/telegram', [TelegramAuthController::class, 'callback'])->name('auth.telegram');

    // Password Reset
    Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Protected user area ──────────────────────────────────────────────────────

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/profile', [DashboardController::class, 'updateProfile'])->name('dashboard.profile.update');
    Route::post('/dashboard/collections', [DashboardController::class, 'storeCollection'])->name('dashboard.collections.store');
    Route::post('/dashboard/collections/{collection}/toggle/{product}', [DashboardController::class, 'toggleCollectionProduct'])->name('dashboard.collections.toggle');
    
    Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/download/{product:slug}', [DownloadController::class, 'download'])->name('products.download');
    Route::post('/products/{product:slug}/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/products/{product:slug}/wishlist', [\App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle');
});

