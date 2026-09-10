<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\DiscountProduct;
use App\Models\Pembayaran;
use App\Models\Penjualan;
use App\Models\PengaturanWeb;
use App\Models\Pengguna;
use App\Models\Produk;
use App\Models\ProdukVarian;
use App\Models\StokBarang;
use App\Models\UserAddress;
use App\Models\UserVoucher;
use App\Models\Voucher;
use App\Models\Wishlist;
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

        $recommendedProducts = Produk::with(['brand', 'fotoUtama'])
            ->where('status', 'aktif')
            ->orderByDesc('is_popular')
            ->orderByDesc('is_newproduct')
            ->inRandomOrder()
            ->take(4)
            ->get();

        $activeVouchers = \App\Models\Voucher::where('status', 'active')
            ->where('start_at', '<=', now())
            ->where('end_at', '>=', now())
            ->where(function ($q) {
                $q->whereNull('quota')->orWhereColumn('used_count', '<', 'quota');
            })
            ->orderBy('end_at')
            ->take(2)
            ->get();

        return view('dashboard', compact(
            'totalOrders',
            'completed',
            'processing',
            'recentOrders',
            'recommendedProducts',
            'activeVouchers'
        ));
    }

    public function pesanan(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        $query = Penjualan::with(['detail.barang', 'shipment', 'address'])
            ->where('order_web', true)
            ->where('created_by', $user->id);

        if ($request->filled('q')) {
            $keyword = trim($request->q);
            $query->where(function ($q) use ($keyword) {
                $q->where('kode_penjualan', 'like', '%' . $keyword . '%')
                    ->orWhere('nomor_pesanan', 'like', '%' . $keyword . '%')
                    ->orWhereHas('detail.barang', function ($q) use ($keyword) {
                        $q->where('nama_barang', 'like', '%' . $keyword . '%')
                            ->orWhereHas('produk', function ($q) use ($keyword) {
                                $q->where('nama_produk', 'like', '%' . $keyword . '%');
                            });
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from')) {
            $query->whereDate('tanggal', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('tanggal', '<=', $request->to);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        $pembayarans = Pembayaran::whereIn('penjualan_id', $orders->pluck('id'))->get()->keyBy('penjualan_id');
        $orders->each(function ($order) use ($pembayarans) {
            $order->setRelation('pembayaran', $pembayarans->get($order->id));
        });

        return view('dashboard-pesanan', [
            'orders' => $orders,
            'filters' => $request->only(['q', 'status', 'from', 'to']),
        ]);
    }

    public function pesananDetail($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $order = Penjualan::with(['detail.barang', 'address', 'shipment'])
            ->where('order_web', true)
            ->where('created_by', Auth::id())
            ->findOrFail($id);

        $order->setRelation('pembayaran', Pembayaran::where('penjualan_id', $order->id)->first());

        return view('dashboard-pesanan-detail', compact('order'));
    }

    public function pembayaran(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();

        $query = Pembayaran::with([
            'penjualanDraft' => fn ($q) => $q->with('items.barang.produk'),
            'penjualan',
        ])
            ->where(function ($q) use ($userId) {
                $q->whereHas('penjualanDraft', function ($q) use ($userId) {
                    $q->where('created_by', $userId);
                })->orWhereHas('penjualan', function ($q) use ($userId) {
                    $q->where('created_by', $userId);
                });
            });

        if ($request->filled('q')) {
            $keyword = trim($request->q);
            $query->where(function ($q) use ($keyword) {
                $q->whereHas('penjualanDraft', function ($q) use ($keyword) {
                    $q->where('kode_penjualan', 'like', '%' . $keyword . '%')
                        ->orWhereHas('items', function ($q) use ($keyword) {
                            $q->whereHas('barang', function ($q) use ($keyword) {
                                $q->where('nama_barang', 'like', '%' . $keyword . '%')
                                    ->orWhereHas('produk', function ($q) use ($keyword) {
                                        $q->where('nama_produk', 'like', '%' . $keyword . '%');
                                    });
                            });
                        });
                })->orWhereHas('penjualan', function ($q) use ($keyword) {
                    $q->where('kode_penjualan', 'like', '%' . $keyword . '%');
                });
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'gagal') {
                $query->whereIn('status', ['deny', 'cancel', 'expire', 'failure']);
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $payments = $query->orderByRaw(
            "CASE WHEN status = 'pending' THEN 0 ELSE 1 END"
        )->orderBy('created_at', 'desc')->get();

        $pendingCount = $payments->where('status', 'pending')->count();

        return view('dashboard-pembayaran', [
            'payments' => $payments,
            'pendingCount' => $pendingCount,
            'filters' => $request->only(['q', 'status', 'from', 'to']),
        ]);
    }

    public function wishlist()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $wishlistItems = Wishlist::with(['barang.produk.brand', 'barang.produk.fotoUtama', 'produk.brand', 'produk.fotoUtama'])
            ->where('user_id', Auth::id())
            ->where('status', 'aktif')
            ->orderByDesc('created_at')
            ->get()
            ->unique('produk_id')
            ->values();

        return view('dashboard-wishlist', compact('wishlistItems'));
    }

    public function toggleWishlist(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['ok' => false, 'message' => 'Silakan login terlebih dahulu.'], 401);
        }

        $request->validate([
            'produk_id' => 'required|integer',
            'barang_id' => 'nullable|integer',
        ]);

        $produk = \App\Models\Produk::find($request->produk_id);
        if (!$produk) {
            return response()->json(['ok' => false, 'message' => 'Produk tidak ditemukan.'], 404);
        }

        // Cek apakah produk sudah ada di wishlist user (unique by produk_id)
        $existingAktif = Wishlist::where('user_id', Auth::id())
            ->where('produk_id', $request->produk_id)
            ->where('status', 'aktif')
            ->first();

        if ($existingAktif) {
            $existingAktif->update(['status' => 'nonaktif']);
            $count = Wishlist::where('user_id', Auth::id())->where('status', 'aktif')->get()->unique('produk_id')->count();
            return response()->json(['ok' => true, 'wishlisted' => false, 'message' => 'Dihapus dari wishlist.', 'count' => $count]);
        }

        // Double wishlist prevention: cek apakah ada record nonaktif untuk produk ini (reactivate)
        $existingNonAktif = Wishlist::where('user_id', Auth::id())
            ->where('produk_id', $request->produk_id)
            ->where('status', 'nonaktif')
            ->first();

        // Ambil barang_id representative: pakai barang_id yang dikirim atau fallback ke barang pertama produk
        $barangId = $request->barang_id;
        if (!$barangId) {
            $firstBarang = \App\Models\Barang::where('produk_id', $produk->id)->first();
            $barangId = $firstBarang ? $firstBarang->id : null;
        }

        if (!$barangId) {
            return response()->json(['ok' => false, 'message' => 'Varian produk tidak tersedia.'], 422);
        }

        if ($existingNonAktif) {
            $existingNonAktif->update(['status' => 'aktif', 'barang_id' => $barangId]);
            $count = Wishlist::where('user_id', Auth::id())->where('status', 'aktif')->get()->unique('produk_id')->count();
            return response()->json([
                'ok' => true,
                'wishlisted' => true,
                'message' => 'Ditambahkan ke wishlist.',
                'count' => $count,
            ]);
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'barang_id' => $barangId,
            'produk_id' => $request->produk_id,
            'status' => 'aktif',
        ]);

        $count = Wishlist::where('user_id', Auth::id())->where('status', 'aktif')->get()->unique('produk_id')->count();
        return response()->json([
            'ok' => true,
            'wishlisted' => true,
            'message' => 'Ditambahkan ke wishlist.',
            'count' => $count,
        ]);
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
            'latitude'      => 'required|numeric|between:-90,90',
            'longitude'     => 'required|numeric|between:-180,180',
            'catatan'       => 'nullable|string|max:500',
        ]);

        $data = $request->only(['label', 'receiver_name', 'phone', 'province', 'city', 'district', 'postal_code', 'address', 'latitude', 'longitude', 'catatan']);
        $data['user_id'] = Auth::id();

        $hasAddresses = Auth::user()->addresses()->exists();

        if ($request->boolean('is_default')) {
            Auth::user()->addresses()->update(['is_default' => false]);
            $data['is_default'] = true;
        } else {
            $data['is_default'] = !$hasAddresses;
        }

        $areaId = $request->input('area_id');
        if (!$areaId) {
            $areaId = $this->lookupBiteshipArea($data['district'] ?? '', $data['city'], $data['province'], $data['postal_code']);
        }
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
            'latitude'      => 'required|numeric|between:-90,90',
            'longitude'     => 'required|numeric|between:-180,180',
            'catatan'       => 'nullable|string|max:500',
        ]);

        $data = $request->only(['label', 'receiver_name', 'phone', 'province', 'city', 'district', 'postal_code', 'address', 'latitude', 'longitude', 'catatan']);

        if ($request->boolean('is_default')) {
            Auth::user()->addresses()->where('id', '!=', $id)->update(['is_default' => false]);
            $data['is_default'] = true;
        }

        $areaId = $request->input('area_id');
        if (!$areaId) {
            $areaId = $this->lookupBiteshipArea($data['district'] ?? '', $data['city'], $data['province'], $data['postal_code']);
        }
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

    public function geocodeAddress(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['results' => []], 401);
        }

        $q = trim((string) $request->input('q', ''));
        if (mb_strlen($q) < 3) {
            return response()->json(['results' => []]);
        }

        $key = $this->googleMapsKey();
        if ($key) {
            $data = $this->googlePlaceAutocomplete($q);

            return response()->json([
                'provider' => 'google',
                'results' => array_map(fn ($p) => $this->normalizeGooglePrediction($p), $data['predictions'] ?? []),
            ]);
        }

        $places = $this->nominatimSearch($q);

        return response()->json([
            'provider' => 'nominatim',
            'results' => array_map(fn ($p) => $this->normalizeNominatimPlace($p), $places),
        ]);
    }

    public function placeDetails(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $placeId = trim((string) $request->input('place_id', ''));
        $key = $this->googleMapsKey();

        if (!$key || !$placeId) {
            return response()->json(['error' => 'Lokasi tidak ditemukan.'], 404);
        }

        $place = $this->googlePlaceDetails($placeId);
        if (!$place) {
            return response()->json(['error' => 'Lokasi tidak ditemukan.'], 404);
        }

        return response()->json(['result' => $this->normalizeGooglePlace($place)]);
    }

    public function reverseGeocode(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $lat = (float) $request->input('lat');
        $lng = (float) $request->input('lng');

        if (abs($lat) > 90 || abs($lng) > 180) {
            return response()->json(['error' => 'Koordinat tidak valid.'], 422);
        }

        $key = $this->googleMapsKey();
        if ($key) {
            $place = $this->googleReverseGeocode($lat, $lng);
            if (!$place) {
                return response()->json(['error' => 'Lokasi tidak ditemukan.'], 404);
            }

            return response()->json(['result' => $this->normalizeGooglePlace($place)]);
        }

        $place = $this->nominatimReverse($lat, $lng);
        if (!$place) {
            return response()->json(['error' => 'Lokasi tidak ditemukan.'], 404);
        }

        return response()->json(['result' => $this->normalizeNominatimPlace($place)]);
    }

    public function resolveArea(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $area = $this->lookupBiteshipAreaDetails(
            trim((string) $request->input('district', '')),
            trim((string) $request->input('city', '')),
            trim((string) $request->input('province', '')),
            trim((string) $request->input('postal_code', ''))
        );

        return response()->json([
            'area_id' => $area['id'] ?? null,
            'area' => $area ? [
                'province'    => $area['administrative_division_level_1_name'] ?? '',
                'city'        => $area['administrative_division_level_2_name'] ?? '',
                'district'    => $area['administrative_division_level_3_name'] ?? '',
                'postal_code' => $area['postal_code'] ?? '',
            ] : null,
        ]);
    }

    private function googleMapsKey()
    {
        $key = config('services.google.maps_api_key');

        if (!$key) {
            $key = PengaturanWeb::where('key', 'Google Maps API Key')->value('value');
        }

        return $key ? trim((string) $key) : null;
    }

    private function googlePlaceAutocomplete($query)
    {
        $url = 'https://maps.googleapis.com/maps/api/place/autocomplete/json?'
            . http_build_query([
                'input' => $query,
                'key' => $this->googleMapsKey(),
                'components' => 'country:ID',
                'language' => 'id',
                'types' => 'address',
            ]);

        $data = $this->googleMapsRequest($url);

        return is_array($data) && ($data['status'] ?? '') === 'OK' ? $data : [];
    }

    private function googlePlaceDetails($placeId)
    {
        $url = 'https://maps.googleapis.com/maps/api/place/details/json?'
            . http_build_query([
                'place_id' => $placeId,
                'key' => $this->googleMapsKey(),
                'fields' => 'formatted_address,geometry,address_component,name',
                'language' => 'id',
            ]);

        $data = $this->googleMapsRequest($url);

        return is_array($data) && ($data['status'] ?? '') === 'OK' ? ($data['result'] ?? null) : null;
    }

    private function googleReverseGeocode($lat, $lng)
    {
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?'
            . http_build_query([
                'latlng' => $lat . ',' . $lng,
                'key' => $this->googleMapsKey(),
                'language' => 'id',
            ]);

        $data = $this->googleMapsRequest($url);

        if (!is_array($data) || ($data['status'] ?? '') !== 'OK' || empty($data['results'])) {
            return null;
        }

        return $data['results'][0];
    }

    private function googleMapsRequest($url)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_REFERER => 'https://christoperbale.id',
            CURLOPT_TIMEOUT => 8,
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || !$response) {
            return null;
        }

        return json_decode($response, true);
    }

    private function normalizeGooglePrediction(array $prediction): array
    {
        $structured = $prediction['structured_formatting'] ?? [];

        return [
            'provider' => 'google',
            'place_id' => $prediction['place_id'] ?? null,
            'address' => $prediction['description'] ?? '',
            'main_text' => $structured['main_text'] ?? '',
            'secondary_text' => $structured['secondary_text'] ?? '',
        ];
    }

    private function normalizeGooglePlace(array $place): array
    {
        $components = $place['address_components'] ?? [];
        $geo = (array) ($place['geometry'] ?? []);
        $loc = $geo['location'] ?? [];

        $parts = $this->mapGoogleComponents($components);

        $streetNames = array_merge($parts['street_names']);
        $street = implode(' ', array_slice($streetNames, 0, 2));

        return [
            'provider' => 'google',
            'place_id' => $place['place_id'] ?? null,
            'address' => $place['formatted_address'] ?? '',
            'street' => $street,
            'province' => $parts['province'],
            'city' => $parts['city'],
            'district' => $parts['district'],
            'subdistrict' => $parts['subdistrict'],
            'postal_code' => $parts['postal'],
            'lat' => (float) ($location['lat'] ?? 0),
            'lng' => (float) ($location['lng'] ?? 0),
        ];
    }

    private function mapGoogleComponents(array $components): array
    {
        $out = [
            'province' => '',
            'city' => '',
            'district' => '',
            'subdistrict' => '',
            'postal' => '',
            'street_names' => [],
        ];

        foreach ($components as $c) {
            $types = $c['types'] ?? [];
            $name = $c['long_name'] ?? '';

            if (in_array('postal_code', $types)) {
                $out['postal'] = $name;
            } elseif (in_array('administrative_area_level_1', $types)) {
                $out['province'] = $name;
            } elseif (in_array('administrative_area_level_2', $types)) {
                $out['city'] = $name;
            } elseif (in_array('administrative_area_level_3', $types)) {
                $out['district'] = $name;
            } elseif (in_array('administrative_area_level_4', $types)) {
                $out['subdistrict'] = $name;
            } elseif (in_array('locality', $types)) {
                $out['city'] = $name;
            } elseif (in_array('neighborhood', $types)) {
                $out['subdistrict'] = $out['subdistrict'] ?: $name;
            } elseif (in_array('street_number', $types) || in_array('route', $types)) {
                $out['street_names'][] = $name;
            }
        }

        $out['province'] = $this->provinceFromState($out['province']);

        return $out;
    }

    private function nominatimSearch($query)
    {
        $url = 'https://nominatim.openstreetmap.org/search?'
            . http_build_query([
                'q' => $query,
                'countrycodes' => 'id',
                'format' => 'jsonv2',
                'addressdetails' => 1,
                'limit' => 8,
            ]);

        return $this->nominatimRequest($url);
    }

    private function nominatimReverse($lat, $lng)
    {
        $url = 'https://nominatim.openstreetmap.org/reverse?'
            . http_build_query([
                'lat' => $lat,
                'lon' => $lng,
                'format' => 'jsonv2',
                'addressdetails' => 1,
                'zoom' => 18,
            ]);

        $data = $this->nominatimRequest($url);

        return is_array($data) && !empty($data['display_name']) ? $data : null;
    }

    private function nominatimRequest($url)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => 'CHRISBALE-Web/1.0 (contact: store@chrisbale.id)',
            CURLOPT_REFERER => 'https://christoperbale.id',
            CURLOPT_TIMEOUT => 8,
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || !$response) {
            return [];
        }

        $data = json_decode($response, true);

        return is_array($data) ? $data : [];
    }

    private function normalizeNominatimPlace(array $place): array
    {
        $address = $place['address'] ?? [];
        $display = (string) ($place['display_name'] ?? '');

        $province = $this->provinceFromState($address['state'] ?? '');
        if (!$province) {
            $province = $this->provinceFromIso($address['ISO3166-2-lvl4'] ?? '');
        }
        if (!$province) {
            $province = $this->provinceFromDisplay($display);
        }

        $city = $address['city'] ?? $address['town'] ?? $address['municipality'] ?? $address['county'] ?? '';

        $district = $address['county'] ?? $address['city_district'] ?? $address['district'] ?? $address['suburb'] ?? $address['village'] ?? $address['neighbourhood'] ?? '';

        $subdistrict = $address['suburb'] ?? $address['neighbourhood'] ?? '';

        $postal = $address['postcode'] ?? '';

        $streetParts = array_filter([
            $address['house_number'] ?? null,
            $address['road'] ?? null,
        ]);
        $street = trim(implode(' ', $streetParts));

        if (!$district && $subdistrict) {
            $district = $subdistrict;
        }

        $display = trim(preg_replace('/,\s*Indonesia\s*$/i', '', $display));

        return [
            'address'      => $display,
            'street'       => $street,
            'province'     => $province,
            'city'         => $city,
            'district'     => $district,
            'subdistrict'  => $subdistrict,
            'postal_code'  => $postal,
            'lat'          => (float) ($place['lat'] ?? 0),
            'lng'          => (float) ($place['lon'] ?? 0),
            'osm_type'     => $place['osm_type'] ?? '',
            'osm_id'       => $place['osm_id'] ?? '',
        ];
    }

    private function provinceFromIso($iso)
    {
        $iso = strtoupper(trim((string) $iso));

        $map = [
            'ID-AC' => 'Aceh',
            'ID-SU' => 'Sumatera Utara',
            'ID-SB' => 'Sumatera Barat',
            'ID-RI' => 'Riau',
            'ID-JA' => 'Jambi',
            'ID-SS' => 'Sumatera Selatan',
            'ID-BE' => 'Bengkulu',
            'ID-LA' => 'Lampung',
            'ID-KB' => 'Kepulauan Bangka Belitung',
            'ID-KR' => 'Kepulauan Riau',
            'ID-JK' => 'DKI Jakarta',
            'ID-JB' => 'Jawa Barat',
            'ID-JT' => 'Jawa Tengah',
            'ID-YO' => 'DI Yogyakarta',
            'ID-JI' => 'Jawa Timur',
            'ID-BT' => 'Banten',
            'ID-BA' => 'Bali',
            'ID-NB' => 'Nusa Tenggara Barat',
            'ID-NT' => 'Nusa Tenggara Timur',
            'ID-KB' => 'Kalimantan Barat',
            'ID-KT' => 'Kalimantan Tengah',
            'ID-KS' => 'Kalimantan Selatan',
            'ID-KI' => 'Kalimantan Timur',
            'ID-KU' => 'Kalimantan Utara',
            'ID-SL' => 'Sulawesi Utara',
            'ID-ST' => 'Sulawesi Tengah',
            'ID-SG' => 'Sulawesi Selatan',
            'ID-SR' => 'Sulawesi Barat',
            'ID-SN' => 'Sulawesi Tenggara',
            'ID-GO' => 'Gorontalo',
            'ID-MA' => 'Maluku',
            'ID-MU' => 'Maluku Utara',
            'ID-PA' => 'Papua',
            'ID-PB' => 'Papua Barat',
        ];

        return $map[$iso] ?? '';
    }

    private function provinceFromDisplay($display)
    {
        $parts = array_map('trim', explode(',', (string) $display));
        if (count($parts) < 2) {
            return '';
        }

        if (strtolower((string) end($parts)) === 'indonesia') {
            array_pop($parts);
        }

        if ($parts && preg_match('/^\d{4,6}$/', (string) end($parts))) {
            array_pop($parts);
        }

        if (!$parts) {
            return '';
        }

        $province = $this->provinceFromState((string) end($parts));

        return $province ?: (string) end($parts);
    }

    private function provinceFromState($state)
    {
        $state = trim((string) $state);
        if (!$state) {
            return '';
        }

        $map = [
            'jakarta' => 'DKI Jakarta',
            'daerah khusus ibukota jakarta' => 'DKI Jakarta',
            'jawa barat' => 'Jawa Barat',
            'jawa tengah' => 'Jawa Tengah',
            'yogyakarta' => 'DI Yogyakarta',
            'jawa timur' => 'Jawa Timur',
            'banten' => 'Banten',
            'bali' => 'Bali',
            'sumatera utara' => 'Sumatera Utara',
            'sumatera selatan' => 'Sumatera Selatan',
            'kalimantan timur' => 'Kalimantan Timur',
            'west java' => 'Jawa Barat',
            'central java' => 'Jawa Tengah',
            'east java' => 'Jawa Timur',
            'north sumatra' => 'Sumatera Utara',
            'south sumatra' => 'Sumatera Selatan',
            'east kalimantan' => 'Kalimantan Timur',
        ];

        $key = mb_strtolower($state);

        foreach ($map as $needle => $province) {
            if (str_contains($key, $needle)) {
                return $province;
            }
        }

        return $state;
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
            $item->stok = StokBarang::where('barang_id', $item->barang_id)->value('jumlah_stok') ?? 0;
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

    public function updateCartItem(Request $request, $id)
    {
        $item = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $qty = max(1, min((int) $request->input('qty', 1), 99));
        $stok = StokBarang::where('barang_id', $item->barang_id)->value('jumlah_stok') ?? 0;
        $oldQty = (int) $item->qty;

        if ($qty > $stok) {
            // Izinkan penurunan qty meski masih di atas stok (mis. 5→4 saat stok 3),
            // tapi tolak penambahan/pertahankan qty yang melebihi stok.
            if ($qty >= $oldQty) {
                return response()->json([
                    'ok' => false,
                    'message' => $stok <= 0
                        ? 'Stok produk ini sedang habis.'
                        : 'Stok tidak cukup. Stok tersedia: ' . $stok . '.',
                ], 422);
            }
        }

        $item->update(['qty' => $qty]);

        return response()->json(['ok' => true, 'qty' => $qty]);
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

        $available = StokBarang::where('barang_id', $varian->barang_id)->value('jumlah_stok') ?? 0;

        $existing = Cart::where('user_id', Auth::id())
            ->where('barang_id', $varian->barang_id)
            ->first();

        $existingQty = $existing->qty ?? 0;
        $totalQty = $existingQty + $request->qty;

        if ($available <= 0 || $totalQty > $available) {
            return response()->json([
                'ok' => false,
                'message' => $available <= 0
                    ? 'Stok produk ini habis.'
                    : 'Stok tidak cukup. Sisa stok ' . $available . ($existingQty > 0 ? ' (sudah ' . $existingQty . ' di keranjang Anda)' : '') . '.',
            ], 422);
        }

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

        return response()->json([
            'ok' => true,
            'message' => 'Produk ditambahkan ke keranjang.',
            'cart_count' => Cart::where('user_id', Auth::id())->count(),
        ]);
    }

    public function addToCartSimple(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|integer',
            'qty' => 'integer|min:1|max:99',
        ]);

        $qty = $request->qty ?? 1;

        // Ambil varian pertama yang punya stok
        $varian = ProdukVarian::where('produk_id', $request->produk_id)
            ->whereHas('stok', function ($q) {
                $q->where('jumlah_stok', '>', 0);
            })
            ->first();

        if (!$varian) {
            return response()->json(['ok' => false, 'message' => 'Produk tidak tersedia atau stok habis.'], 422);
        }

        $available = StokBarang::where('barang_id', $varian->barang_id)->value('jumlah_stok') ?? 0;

        $existing = Cart::where('user_id', Auth::id())
            ->where('barang_id', $varian->barang_id)
            ->first();

        $existingQty = $existing->qty ?? 0;
        $totalQty = $existingQty + $qty;

        if ($available <= 0 || $totalQty > $available) {
            return response()->json([
                'ok' => false,
                'message' => $available <= 0
                    ? 'Stok produk ini habis.'
                    : 'Stok tidak cukup. Sisa stok ' . $available . ($existingQty > 0 ? ' (sudah ' . $existingQty . ' di keranjang Anda)' : '') . '.',
            ], 422);
        }

        if ($existing) {
            $newQty = $existing->qty + $qty;
            $existing->update(['qty' => min($newQty, 99)]);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'barang_id' => $varian->barang_id,
                'qty' => $qty,
            ]);
        }

        return response()->json([
            'ok' => true,
            'success' => true,
            'message' => 'Produk ditambahkan ke keranjang.',
            'cart_count' => Cart::where('user_id', Auth::id())->count(),
        ]);
    }

    public function addToCartFromWishlist(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['ok' => false, 'message' => 'Silakan login terlebih dahulu.'], 401);
        }

        $request->validate([
            'barang_id' => 'required|integer',
            'qty' => 'integer|min:1|max:99',
        ]);

        $qty = $request->qty ?? 1;

        $barang = \App\Models\Barang::with('stok')->find($request->barang_id);
        if (!$barang) {
            return response()->json(['ok' => false, 'message' => 'Produk tidak ditemukan.'], 404);
        }

        $available = $barang->stok->jumlah_stok ?? 0;

        $existing = Cart::where('user_id', Auth::id())
            ->where('barang_id', $request->barang_id)
            ->first();

        $existingQty = $existing->qty ?? 0;
        $totalQty = $existingQty + $qty;

        if ($available <= 0 || $totalQty > $available) {
            return response()->json([
                'ok' => false,
                'message' => $available <= 0
                    ? 'Stok produk ini habis.'
                    : 'Stok tidak cukup. Sisa stok ' . $available . ($existingQty > 0 ? ' (sudah ' . $existingQty . ' di keranjang Anda)' : '') . '.',
            ], 422);
        }

        if ($existing) {
            $newQty = $existing->qty + $qty;
            $existing->update(['qty' => min($newQty, 99)]);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'barang_id' => $request->barang_id,
                'qty' => $qty,
            ]);
        }

        return response()->json([
            'ok' => true,
            'success' => true,
            'message' => 'Produk ditambahkan ke keranjang.',
            'cart_count' => Cart::where('user_id', Auth::id())->count(),
        ]);
    }

    private function lookupBiteshipArea($district, $city, $province, $postalCode)
    {
        $area = $this->lookupBiteshipAreaDetails($district, $city, $province, $postalCode);

        return $area['id'] ?? null;
    }

    private function lookupBiteshipAreaDetails($district, $city, $province, $postalCode)
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

        $candidates = $this->pickBiteshipArea($data['areas'], $postalCode);
        $areaId = $candidates['id'] ?? null;
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
            return $candidates;
        }

        $data2 = json_decode($response2, true);
        if (empty($data2['success']) || empty($data2['areas'])) {
            return $candidates;
        }

        return $this->pickBiteshipArea($data2['areas'], $postalCode);
    }

    private function pickBiteshipArea(array $areas, $postalCode)
    {
        $postal = preg_replace('/\D/', '', (string) $postalCode);

        foreach ($areas as $area) {
            $areaPostal = preg_replace('/\D/', '', (string) ($area['postal_code'] ?? ''));
            if ($postal && $areaPostal === $postal) {
                return $area;
            }
        }

        return $areas[0];
    }
}
