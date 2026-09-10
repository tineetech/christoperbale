<?php

namespace App\Http\Controllers;

use App\Models\Produk;

class HomeController extends Controller
{
    public function index()
    {
        $popularProducts = Produk::with(['brand', 'fotoUtama'])
            ->where('is_popular', true)
            ->where('status', 'aktif')
            ->orderByDesc('updated_at')
            ->get();

        $newProducts = Produk::with(['brand', 'fotoUtama'])
            ->where('is_newproduct', true)
            ->where('status', 'aktif')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        return view('home', [
            'popularProducts' => $popularProducts,
            'newProducts' => $newProducts,
        ]);
    }
}
