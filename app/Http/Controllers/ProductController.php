<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\DiscountProduct;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\ProdukVarian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    private function productDiscount($produk)
    {
        $activeDiscounts = DiscountProduct::with('discount')
            ->where('product_id', $produk->id)
            ->where('status', 'active')
            ->get()
            ->filter(fn($dp) => $dp->discount);

        if ($activeDiscounts->isEmpty()) {
            return [
                'price' => (float) $produk->harga_normal,
                'old' => null,
                'badge' => null,
            ];
        }

        $totalPercent = 0;
        $totalFixed = 0;

        foreach ($activeDiscounts as $dp) {
            $d = $dp->discount;
            if ($d->type === 'percentage') {
                $totalPercent += (float) $d->value;
            } elseif ($d->type === 'fixed') {
                $totalFixed += (float) $d->value;
            }
        }

        $price = (float) $produk->harga_normal;
        $price = $price * (1 - $totalPercent / 100);
        $price = max(0, $price - $totalFixed);

        return [
            'price' => $price,
            'old' => (float) $produk->harga_normal,
            'badge' => 'sale',
        ];
    }

    public function index($brand = null)
    {
        $query = Produk::with(['brand', 'fotoUtama'])->where('status', 'aktif');

        $selectedBrand = null;
        if ($brand) {
            $selectedBrand = Brand::where('status_brand', 'aktif')
                ->whereRaw('LOWER(nama_brand) = ?', [strtolower($brand)])
                ->first();
            if ($selectedBrand) {
                $query->where('brand_id', $selectedBrand->id);
            }
        }

        $products = $query->get();

        $allBrands = Brand::where('status_brand', 'aktif')->get();

        $formatted = $products->map(function ($p) {
            $imgUrl = $p->fotoUtama
                ? env('BE_URL') . '/storage/' . $p->fotoUtama->foto
                : null;

            $disc = $this->productDiscount($p);

            $badge = $disc['badge'];
            if (!$badge && $p->created_at && $p->created_at->diffInDays(now()) < 30) {
                $badge = 'new';
            }

            return [
                'slug' => $p->slug,
                'name' => $p->nama_produk,
                'brand' => $p->brand->nama_brand ?? '',
                'price' => (float) $disc['price'],
                'old' => $disc['old'],
                'badge' => $badge,
                'img' => $imgUrl,
                'category' => '',
            ];
        });

        return view('products', [
            'brand' => $selectedBrand?->nama_brand,
            'allProducts' => $formatted->toArray(),
            'brands' => $allBrands,
        ]);
    }

    public function show($slug)
    {
        $product = Produk::with(['brand', 'foto', 'fotoUtama', 'barang'])->where('slug', $slug)->firstOrFail();

        $imgUrl = $product->fotoUtama
            ? env('BE_URL') . '/storage/' . $product->fotoUtama->foto
            : null;

        $imgs = $product->foto->sortBy('urutan')->map(fn($f) => env('BE_URL') . '/storage/' . $f->foto)->toArray();
        if (empty($imgs) && $imgUrl) {
            $imgs = [$imgUrl];
        }

        $disc = $this->productDiscount($product);

        $badge = $disc['badge'];
        if (!$badge && $product->created_at && $product->created_at->diffInDays(now()) < 30) {
            $badge = 'new';
        }

        // Varian (size + color) with stock check
        $varian = ProdukVarian::with('barang.stok')
            ->where('produk_id', $product->id)
            ->get();

        $sizes = $varian->groupBy('size')->map(function ($group) {
            $available = $group->contains(fn($v) => $v->barang && $v->barang->stok && $v->barang->stok->jumlah_stok >= 1);
            return ['value' => $group->first()->size, 'available' => $available];
        })->values()->toArray();

        $colorRules = [
            [['baby', 'pink'], '#F4C2C2'],
            [['burgundy'], '#800020'],
            [['cream'], '#FFFDD0'],
            [['hitam', 'black'], '#111111'],
            [['putih', 'white'], '#F5F5F5'],
            [['abu'], '#616161'],
            [['navy', 'biru'], '#1A237E'],
            [['merah', 'red'], '#D32F2F'],
            [['cokelat', 'coklat', 'brown'], '#5D4037'],
            [['krem', 'beige'], '#FFF8E1'],
            [['emas', 'gold'], '#C7A252'],
            [['hijau', 'green', 'army'], '#4B5320'],
            [['kuning', 'yellow'], '#FFD700'],
            [['ungu', 'purple'], '#8E24AA'],
            [['jingga', 'orange'], '#FF6F00'],
        ];

        $colors = $varian->groupBy('warna')->map(function ($group) use ($colorRules) {
            $name = $group->first()->warna;
            $lower = strtolower($name);
            $hex = '#333333';
            foreach ($colorRules as [$keywords, $color]) {
                foreach ($keywords as $kw) {
                    if (str_contains($lower, $kw)) {
                        $hex = $color;
                        break 2;
                    }
                }
            }
            $available = $group->contains(fn($v) => $v->barang && $v->barang->stok && $v->barang->stok->jumlah_stok >= 1);
            return [
                'name' => $name,
                'hex' => $hex,
                'available' => $available,
            ];
        })->values()->toArray();

        // Review count: distinct penjualan that include this product
        $barangIds = $product->barang->pluck('id');
        $reviewCount = PenjualanDetail::whereIn('barang_id', $barangIds)
            ->distinct('penjualan_id')
            ->count('penjualan_id');

        $allProducts = Produk::with(['brand', 'fotoUtama'])
            ->where('status', 'aktif')
            ->where('id', '!=', $product->id)
            ->take(8)
            ->get()
            ->map(function ($p) {
                $disc = $this->productDiscount($p);
                return [
                    'slug' => $p->slug,
                    'name' => $p->nama_produk,
                    'brand' => $p->brand->nama_brand ?? '',
                    'price' => $disc['price'],
                    'old' => $disc['old'],
                    'badge' => $disc['badge'],
                    'img' => $p->fotoUtama ? env('BE_URL') . '/storage/' . $p->fotoUtama->foto : null,
                    'category' => '',
                ];
            })
            ->toArray();

        $productData = [
            'id' => $product->id,
            'slug' => $product->slug,
            'name' => $product->nama_produk,
            'brand' => $product->brand->nama_brand ?? '',
            'price' => (float) $disc['price'],
            'old' => $disc['old'],
            'badge' => $badge,
            'rating' => 5,
            'review_count' => $reviewCount,
            'total_buyers' => $reviewCount,
            'img' => $imgUrl,
            'imgs' => $imgs,
            'desc' => $product->deskripsi ?? '',
            'features' => [],
            'sizes' => $sizes,
            'colors' => $colors,
            'sku' => '',
            'category' => '',
        ];

        return view('product-detail', [
            'product' => $productData,
            'allProducts' => $allProducts,
        ]);
    }
}
