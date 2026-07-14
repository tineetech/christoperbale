<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/about', [AboutController::class, 'index']);
Route::get('/contact', [ContactController::class, 'index']);
Route::get('/products/{brand?}', [ProductController::class, 'index'])->whereIn('brand', ['chrisbale', 'agatha']);
Route::get('/product/{slug}', [ProductController::class, 'show']);

Route::get('/login', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'store']);
Route::get('/register', [RegisterController::class, 'index']);
Route::post('/register', [RegisterController::class, 'store']);

Route::view('/terms', 'terms');
Route::view('/privacy', 'privacy');

Route::get('/forgot-password', [ForgotPasswordController::class, 'index']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'store']);

Route::get('/reset-password', [ResetPasswordController::class, 'index']);
Route::post('/reset-password', [ResetPasswordController::class, 'store']);

Route::get('/auth/google', [GoogleAuthController::class, 'redirect']);
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);

Route::get('/logout', function() {
    return '<script>localStorage.removeItem("token");window.location.href="/";</script>';
});
