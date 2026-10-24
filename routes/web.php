<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\SeoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/', [HomeController::class, 'index']);
Route::get('/about', [AboutController::class, 'index']);
Route::get('/contact', [ContactController::class, 'index']);
Route::get('/products/{brand?}', [ProductController::class, 'index']);
Route::get('/product/{slug}', [ProductController::class, 'show']);
Route::view('/terms', 'terms');
Route::view('/privacy', 'privacy');

// SEO: sitemap dinamis + robots dinamis (dengan referensi sitemap absolut)
Route::get('/sitemap.xml', [SeoController::class, 'sitemap']);
Route::get('/robots.txt', [SeoController::class, 'robots']);

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
    Route::get('/dashboard/pembayaran', [DashboardController::class, 'pembayaran'])->name('dashboard.pembayaran');
    Route::get('/dashboard/keranjang', [DashboardController::class, 'keranjang'])->name('dashboard.keranjang');
    Route::post('/dashboard/keranjang/tambah', [DashboardController::class, 'addToCartSimple'])->name('dashboard.keranjang.tambah');
    Route::patch('/dashboard/keranjang/{id}', [DashboardController::class, 'updateCartItem'])->name('dashboard.keranjang.update');
    Route::delete('/dashboard/keranjang/{id}', [DashboardController::class, 'deleteCartItem'])->name('dashboard.keranjang.delete');
    Route::post('/cart/add', [DashboardController::class, 'addToCart'])->name('cart.add');
    Route::get('/dashboard/wishlist', [DashboardController::class, 'wishlist'])->name('dashboard.wishlist');
    Route::post('/wishlist/toggle', [DashboardController::class, 'toggleWishlist'])->name('wishlist.toggle');
    Route::post('/wishlist/add-to-cart', [DashboardController::class, 'addToCartFromWishlist'])->name('wishlist.add-to-cart');
    Route::get('/dashboard/profil', [DashboardController::class, 'profil'])->name('dashboard.profil');
    Route::post('/dashboard/profil', [DashboardController::class, 'updateProfile'])->name('dashboard.profil.update');
    Route::post('/dashboard/profil/password', [DashboardController::class, 'updatePassword'])->name('dashboard.profil.password');
    Route::get('/dashboard/alamat', [DashboardController::class, 'alamat'])->name('dashboard.alamat');
    Route::get('/dashboard/alamat/tambah', [DashboardController::class, 'alamatForm'])->name('dashboard.alamat.create');
    Route::get('/dashboard/alamat/{id}/edit', [DashboardController::class, 'alamatFormEdit'])->name('dashboard.alamat.edit');
    Route::post('/dashboard/alamat', [DashboardController::class, 'storeAddress'])->name('dashboard.alamat.store');
    Route::post('/dashboard/alamat/ajax', [DashboardController::class, 'storeAddressAjax'])->name('dashboard.alamat.store.ajax');
    Route::put('/dashboard/alamat/{id}', [DashboardController::class, 'updateAddress'])->name('dashboard.alamat.update');
    Route::delete('/dashboard/alamat/{id}', [DashboardController::class, 'deleteAddress'])->name('dashboard.alamat.delete');
    Route::put('/dashboard/alamat/{id}/default', [DashboardController::class, 'setDefaultAddress'])->name('dashboard.alamat.default');
    Route::get('/dashboard/alamat/geocode', [DashboardController::class, 'geocodeAddress'])->name('dashboard.alamat.geocode');
    Route::post('/dashboard/alamat/reverse', [DashboardController::class, 'reverseGeocode'])->name('dashboard.alamat.reverse');
    Route::post('/dashboard/alamat/area', [DashboardController::class, 'resolveArea'])->name('dashboard.alamat.area');
    Route::post('/dashboard/alamat/details', [DashboardController::class, 'placeDetails'])->name('dashboard.alamat.details');
    Route::get('/dashboard/wilayah/provinces', [DashboardController::class, 'wilayahProvinces'])->name('dashboard.wilayah.provinces');
    Route::get('/dashboard/wilayah/regencies/{code}', [DashboardController::class, 'wilayahRegencies'])->name('dashboard.wilayah.regencies');
    Route::get('/dashboard/wilayah/districts/{code}', [DashboardController::class, 'wilayahDistricts'])->name('dashboard.wilayah.districts');
    Route::get('/dashboard/wilayah/postals', [DashboardController::class, 'wilayahPostals'])->name('dashboard.wilayah.postals');
    Route::get('/dashboard/voucher', [DashboardController::class, 'voucher'])->name('dashboard.voucher');
    Route::get('/dashboard/voucher/{id}', [DashboardController::class, 'voucherDetail'])->name('dashboard.voucher.detail');
    Route::post('/dashboard/voucher/claim', [DashboardController::class, 'claimVoucher'])->name('dashboard.voucher.claim');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/payment/{id}', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::get('/checkout/payment/{id}/status', [CheckoutController::class, 'paymentStatus'])->name('checkout.payment.status');
    Route::get('/checkout/payment/{id}/sync', [CheckoutController::class, 'paymentSync'])->name('checkout.payment.sync');
    Route::get('/checkout/success/{id}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/transfer/{id}', [CheckoutController::class, 'transfer'])->name('checkout.transfer');
    Route::post('/checkout/transfer/{id}/confirm', [CheckoutController::class, 'transferConfirm'])->name('checkout.transfer.confirm');

    Route::get('/logout', function () {
        Auth::guard('web')->logout();
        Session::flush();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    });
});

Route::post('/midtrans/notification', [MidtransController::class, 'notification'])->name('midtrans.notification');
