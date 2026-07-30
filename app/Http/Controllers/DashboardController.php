<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\DiscountProduct;
use App\Models\Penjualan;
use App\Models\PengaturanWeb;
use App\Models\Pengguna;
use App\Models\ProdukVarian;
use App\Models\UserAddress;
use App\Models\UserVoucher;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function overview()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        $orders = Penjualan::with(['detail.barang'])
            ->where('order_web', true)
            ->where('created_by', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalOrders    = $orders->count();
        $completed      = $orders->where('status', 'selesai')->count();
        $processing     = $orders->where('status', '!=', 'selesai')->count();
        $recentOrders   = $orders->take(5);

        return view('dashboard', compact('totalOrders', 'completed', 'processing', 'recentOrders'));
    }

    public function pesanan()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        $orders = Penjualan::with(['detail.barang', 'pembayaran'])
            ->where('order_web', true)
            ->where('created_by', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard-pesanan', compact('orders'));
    }

    public function pesananDetail($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $order = Penjualan::with(['detail.barang', 'pembayaran', 'address', 'shipment'])
            ->where('order_web', true)
            ->where('created_by', Auth::id())
            ->findOrFail($id);

        return view('dashboard-pesanan-detail', compact('order'));
    }

    public function wishlist()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        return view('dashboard-wishlist');
    }

    public function profil()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        return view('dashboard-profil');
    }

    public function alamat()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $addresses = Auth::user()->addresses()->orderBy('is_default', 'desc')->orderBy('created_at', 'desc')->get();

        return view('dashboard-alamat', compact('addresses'));
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'label'         => 'required|string|max:50',
            'receiver_name' => 'required|string|max:100',
            'phone'         => 'required|string|max:20',
            'province'      => 'required|string|max:100',
            'city'          => 'required|string|max:100',
            'district'      => 'required|string|max:100',
            'postal_code'   => 'required|string|max:10',
            'address'       => 'required|string',
        ]);

        $data = $request->only(['label', 'receiver_name', 'phone', 'province', 'city', 'district', 'postal_code', 'address']);
        $data['user_id'] = Auth::id();

        $hasAddresses = Auth::user()->addresses()->exists();

        if ($request->boolean('is_default')) {
            Auth::user()->addresses()->update(['is_default' => false]);
            $data['is_default'] = true;
        } else {
            $data['is_default'] = !$hasAddresses;
        }

        $areaId = $this->lookupBiteshipArea($data['district'] ?? '', $data['city'], $data['province'], $data['postal_code']);
        if ($areaId) {
            $data['area_id'] = $areaId;
        }

        UserAddress::create($data);

        return redirect()->route('dashboard.alamat')->with('success', 'Alamat berhasil ditambahkan.');
    }

    public function updateAddress(Request $request, $id)
    {
        $address = UserAddress::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'label'         => 'required|string|max:50',
            'receiver_name' => 'required|string|max:100',
            'phone'         => 'required|string|max:20',
            'province'      => 'required|string|max:100',
            'city'          => 'required|string|max:100',
            'district'      => 'required|string|max:100',
            'postal_code'   => 'required|string|max:10',
            'address'       => 'required|string',
        ]);

        $data = $request->only(['label', 'receiver_name', 'phone', 'province', 'city', 'district', 'postal_code', 'address']);

        if ($request->boolean('is_default')) {
            Auth::user()->addresses()->where('id', '!=', $id)->update(['is_default' => false]);
            $data['is_default'] = true;
        }

        $areaId = $this->lookupBiteshipArea($data['district'] ?? '', $data['city'], $data['province'], $data['postal_code']);
        if ($areaId) {
            $data['area_id'] = $areaId;
        }

        $address->update($data);

        return redirect()->route('dashboard.alamat')->with('success', 'Alamat berhasil diperbarui.');
    }

    public function deleteAddress($id)
    {
        $address = UserAddress::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault) {
            $newDefault = Auth::user()->addresses()->first();
            if ($newDefault) {
                $newDefault->update(['is_default' => true]);
            }
        }

        return redirect()->route('dashboard.alamat')->with('success', 'Alamat berhasil dihapus.');
    }

    public function setDefaultAddress($id)
    {
        $address = UserAddress::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        Auth::user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return redirect()->route('dashboard.alamat')->with('success', 'Alamat utama berhasil diubah.');
    }

    public function voucher()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userVouchers = Auth::user()->userVouchers()
            ->with('voucher')
            ->whereHas('voucher', function ($q) {
                $q->where('status', 'active');
            })
            ->latest('claimed_at')
            ->get();

        return view('dashboard-voucher', compact('userVouchers'));
    }

    public function voucherDetail($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userVoucher = UserVoucher::with('voucher')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('dashboard-voucher-detail', compact('userVoucher'));
    }

    public function claimVoucher(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
        ]);

        $voucher = Voucher::where('code', $request->code)
            ->where('status', 'active')
            ->first();

        if (!$voucher) {
            return redirect()->route('dashboard.voucher')->with('error', 'Kode voucher tidak ditemukan atau tidak aktif.');
        }

        $now = now();
        // dd($now);

        if ($voucher->start_at && $now < $voucher->start_at) {
            return redirect()->route('dashboard.voucher')->with('error', 'Voucher belum berlaku.');
        }

        if ($voucher->end_at && $now > $voucher->end_at) {
            return redirect()->route('dashboard.voucher')->with('error', 'Voucher sudah kedaluwarsa.');
        }

        if ($voucher->quota && $voucher->used_count >= $voucher->quota) {
            return redirect()->route('dashboard.voucher')->with('error', 'Kuota voucher sudah habis.');
        }

        $userClaimCount = UserVoucher::where('user_id', Auth::id())
            ->where('voucher_id', $voucher->id)
            ->count();

        if ($voucher->claim_limit_per_user && $userClaimCount >= $voucher->claim_limit_per_user) {
            return redirect()->route('dashboard.voucher')->with('error', 'Anda sudah mencapai batas klaim voucher ini.');
        }

        UserVoucher::create([
            'user_id'    => Auth::id(),
            'voucher_id' => $voucher->id,
            'status'     => 'unused',
            'claimed_at' => $now,
            'expired_at' => $voucher->end_at,
        ]);

        $voucher->increment('used_count');

        return redirect()->route('dashboard.voucher')->with('success', 'Voucher berhasil diklaim!');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama'          => 'required|string|max:100',
            'full_name'     => 'nullable|string|max:100',
            'email'         => 'required|email|unique:pengguna,email,' . $user->id,
            'phone'         => 'nullable|string|max:20',
            'gender'        => 'nullable|in:Laki-laki,Perempuan',
            'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->only(['nama', 'full_name', 'email', 'phone', 'gender']);

        if ($request->hasFile('photo_profile')) {
            $path = $request->file('photo_profile')->store('photos/profiles', 'public');
            $data['photo_profile'] = 'storage/' . $path;
        }

        $user->update($data);

        return redirect()->route('dashboard.profil')->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'      => 'required|current_password',
            'password'              => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('dashboard.profil')->with('success', 'Kata sandi berhasil diperbarui.');
    }

    public function keranjang()
    {
        $user = Auth::user();
        $items = Cart::with('barang.produk.brand', 'barang.produkVarian')
            ->where('user_id', $user->id)
            ->get()
            ->sortBy(fn($i) => $i->barang?->produk_id);

        $items->each(function ($item) {
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

            $item->discountPercent = $totalPercent + ($normal > 0 ? round(($totalFixed / $normal) * 100) : 0);
            $item->finalPrice = $discounted;
            $item->hasDiscount = $activeDiscounts->isNotEmpty();

            $varian = $item->barang->produkVarian->first();
            $item->varianSize = $varian?->size ?? '';
            $item->varianColor = $varian?->warna ?? '';
        });

        return view('dashboard-keranjang', [
            'title' => 'Keranjang — CHRISBALE',
            'items' => $items,
        ]);
    }

    public function deleteCartItem($id)
    {
        $item = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $item->delete();
        return response()->json(['ok' => true]);
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|integer',
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'qty' => 'required|integer|min:1|max:99',
        ]);

        $varian = ProdukVarian::where('produk_id', $request->produk_id)
            ->where('size', $request->size)
            ->where('warna', $request->color)
            ->first();

        if (!$varian) {
            return response()->json(['ok' => false, 'message' => 'Varian tidak ditemukan.'], 404);
        }

        $existing = Cart::where('user_id', Auth::id())
            ->where('barang_id', $varian->barang_id)
            ->first();

        if ($existing) {
            $newQty = $existing->qty + $request->qty;
            $existing->update(['qty' => min($newQty, 99)]);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'barang_id' => $varian->barang_id,
                'qty' => $request->qty,
            ]);
        }

        return response()->json(['ok' => true, 'message' => 'Produk ditambahkan ke keranjang.']);
    }

    private function lookupBiteshipArea($district, $city, $province, $postalCode)
    {
        $apiKey = PengaturanWeb::where('key', 'Api Key Biteship')->value('value');
        if (!$apiKey) {
            return null;
        }

        $input = trim($district . ' ' . $city . ' ' . $province . ' ' . $postalCode);
        $input = preg_replace('/\s+/', ' ', $input);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://api.biteship.com/v1/maps/areas?input=' . urlencode($input),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json',
            ],
            CURLOPT_TIMEOUT => 10,
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return null;
        }

        $data = json_decode($response, true);
        if (empty($data['success']) || empty($data['areas'])) {
            return null;
        }

        $areaId = $data['areas'][0]['id'] ?? null;
        if (!$areaId) {
            return null;
        }

        $ch2 = curl_init();
        curl_setopt_array($ch2, [
            CURLOPT_URL => 'https://api.biteship.com/v1/maps/areas/' . $areaId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json',
            ],
            CURLOPT_TIMEOUT => 10,
        ]);
        $response2 = curl_exec($ch2);
        $httpCode2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
        curl_close($ch2);

        if ($httpCode2 !== 200) {
            return $areaId;
        }

        $data2 = json_decode($response2, true);
        return $data2['areas'][0]['id'] ?? $areaId;
    }
}
