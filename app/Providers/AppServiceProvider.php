<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('components.header', function ($view) {
            $view->with('headerBrands', Brand::where('status_brand', 'aktif')->get());
            $view->with('cartCount', Auth::check() ? Cart::where('user_id', Auth::id())->count() : 0);
        });
    }
}
