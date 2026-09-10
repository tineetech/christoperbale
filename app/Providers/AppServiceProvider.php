<?php

namespace App\Providers;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Cart;
use App\Models\ChatbotFaq;
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

        View::composer('components.chatbot', function ($view) {
            $faqs = ChatbotFaq::active()->get();
            $view->with('chatbotFaqs', $faqs);
        });

        View::composer('home', function ($view) {
            $view->with('banners', Banner::active()->orderBy('urutan')->get());
        });
    }
}
