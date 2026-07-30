<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\DiscountProduct;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $cartIds = $request->query('items');
        if (!$cartIds) {
            return redirect()->route('dashboard.keranjang');
        }

        $ids = explode(',', $cartIds);
        $items = Cart::with('barang.produk.brand')
            ->where('user_id', Auth::id())
            ->whereIn('id', $ids)
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('dashboard.keranjang');
        }

        $user = Auth::user();
        $addresses = UserAddress::where('user_id', $user->id)->get()->sortByDesc('is_default');
        $defaultAddress = $addresses->firstWhere('is_default', true) ?? $addresses->first();

        $subtotal = 0;
        $items->each(function ($item) use (&$subtotal) {
            $normal = (float) ($item->barang->produk->harga_normal ?? 0);
            $activeDiscounts = DiscountProduct::with('discount')
                ->where('product_id', $item->barang->produk_id)
                ->where('status', 'active')
                ->get()
                ->filter(fn($dp) => $dp->discount);

            $totalPercent = 0;
            $totalFixed = 0;
            foreach ($activeDiscounts as $dp) {
                $d = $dp->discount;
                if ($d->type === 'percentage') $totalPercent += (float) $d->value;
                elseif ($d->type === 'fixed') $totalFixed += (float) $d->value;
            }

            $discounted = $normal;
            $discounted = $discounted * (1 - $totalPercent / 100);
            $discounted = max(0, $discounted - $totalFixed);

            $item->finalPrice = $discounted;
            $item->normalPrice = $normal;
            $item->hasDiscount = $activeDiscounts->isNotEmpty();
            $item->discountPercent = $totalPercent + ($normal > 0 ? round(($totalFixed / $normal) * 100) : 0);
            $subtotal += $discounted * $item->qty;
        });

        return view('checkout', [
            'items' => $items,
            'user' => $user,
            'addresses' => $addresses,
            'defaultAddress' => $defaultAddress,
            'subtotal' => $subtotal,
        ]);
    }
}
