<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/', [HomeController::class, 'index']);
Route::get('/about', [AboutController::class, 'index']);
Route::get('/contact', [ContactController::class, 'index']);
Route::get('/products/{brand?}', [ProductController::class, 'index'])->whereIn('brand', ['chrisbale', 'agatha']);
Route::get('/product/{slug}', [ProductController::class, 'show']);
Route::view('/terms', 'terms');
Route::view('/privacy', 'privacy');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    Route::get('/register', [RegisterController::class, 'index']);
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/forgot-password', [ForgotPasswordController::class, 'index']);
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store']);
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'index'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'store']);
    Route::get('/auth/google', [GoogleAuthController::class, 'redirect']);
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'overview'])->name('dashboard');
    Route::get('/dashboard/pesanan', [DashboardController::class, 'pesanan'])->name('dashboard.pesanan');
    Route::get('/dashboard/pesanan/{id}', [DashboardController::class, 'pesananDetail'])->name('dashboard.pesanan.detail');
    Route::get('/dashboard/wishlist', [DashboardController::class, 'wishlist'])->name('dashboard.wishlist');
    Route::get('/dashboard/profil', [DashboardController::class, 'profil'])->name('dashboard.profil');
    Route::post('/dashboard/profil', [DashboardController::class, 'updateProfile'])->name('dashboard.profil.update');
    Route::post('/dashboard/profil/password', [DashboardController::class, 'updatePassword'])->name('dashboard.profil.password');
    Route::get('/dashboard/alamat', [DashboardController::class, 'alamat'])->name('dashboard.alamat');
    Route::post('/dashboard/alamat', [DashboardController::class, 'storeAddress'])->name('dashboard.alamat.store');
    Route::put('/dashboard/alamat/{id}', [DashboardController::class, 'updateAddress'])->name('dashboard.alamat.update');
    Route::delete('/dashboard/alamat/{id}', [DashboardController::class, 'deleteAddress'])->name('dashboard.alamat.delete');
    Route::put('/dashboard/alamat/{id}/default', [DashboardController::class, 'setDefaultAddress'])->name('dashboard.alamat.default');
    Route::get('/dashboard/voucher', [DashboardController::class, 'voucher'])->name('dashboard.voucher');
    Route::get('/dashboard/voucher/{id}', [DashboardController::class, 'voucherDetail'])->name('dashboard.voucher.detail');
    Route::post('/dashboard/voucher/claim', [DashboardController::class, 'claimVoucher'])->name('dashboard.voucher.claim');

    Route::get('/logout', function () {
        Auth::guard('web')->logout();
        Session::flush();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    });
});
