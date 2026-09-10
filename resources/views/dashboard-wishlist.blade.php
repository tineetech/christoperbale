@extends('layouts.dashboard')

@section('title', 'Wishlist — CHRISBALE')

@section('dashboard-content')
    <div class="dash-panel" id="panel-wishlist">
        <div class="dash-section-head" style="margin-bottom:24px;">
            <h2 class="dash-section-title">Wishlist Saya</h2>
            @if ($wishlistItems && $wishlistItems->count() > 0)
                <span style="font-size:12px;color:var(--ink-muted);">{{ $wishlistItems->count() }} item tersimpan</span>
            @else
                <span style="font-size:12px;color:var(--ink-muted);">Wishlist kosong</span>
            @endif
        </div>
        @if ($wishlistItems && $wishlistItems->count() > 0)
            <div class="grid-prod" style="grid-template-columns:repeat(auto-fill,minmax(220px,1fr));">
                @foreach ($wishlistItems as $item)
                    @php
                        $produk = $item->produk ?? $item->barang->produk ?? null;
                        $barang = $item->barang;
                        $brand = $produk->brand ?? null;
                        $foto = $produk->fotoUtama ?? null;
                        $price = $barang->harga_1 ?? $produk->harga_normal ?? 0;
                        $discount = $produk->harga_diskon ?? 0;
                    @endphp
                    <article class="prod-card" style="position:relative;cursor:pointer;" onclick="window.location.href='/product/{{ $produk->slug ?? '' }}'">
                        @if ($produk && $produk->is_newproduct)
                            <span class="badge-tag badge-new">Baru</span>
                        @elseif ($produk && $produk->is_popular)
                            <span class="badge-tag badge-hot">Populer</span>
                        @endif
                        <button class="wish-remove-btn" title="Hapus dari wishlist"
                            onclick="event.stopPropagation(); removeWishlistItem({{ $item->produk_id }}, this)">✕</button>
                        <div class="prod-img-wrap2">
                            @if ($foto)
                                <img src="{{ env('BE_URL') . '/storage/' . $foto->foto }}"
                                    alt="{{ $produk->nama_produk ?? 'Produk' }}" loading="lazy">
                            @else
                                <div class="img-placeholder-lg">Chrisbale</div>
                            @endif
                            <div class="prod-overlay">
                                <button class="add-btn"
                                    onclick="event.stopPropagation(); addToCartFromWishlist({{ $barang->id }})">+ Keranjang</button>
                            </div>
                        </div>
                        <div class="prod-info">
                            <h3>{{ $produk->nama_produk ?? $barang->nama_barang ?? 'Produk' }}</h3>
                            <div class="price-row">
                                <span class="current">Rp{{ number_format($price, 0, ',', '.') }}</span>
                                @if ($discount > 0 && $discount < $price)
                                    <span class="old">Rp{{ number_format($discount, 0, ',', '.') }}</span>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;width:100%;min-height:200px;padding:48px 20px;color:var(--ink-muted);background:var(--bg-card);border-radius:var(--radius);border:1px solid var(--line);">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" style="margin-bottom:16px;opacity:0.4;">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                </svg>
                <p style="font-size:14px;">Belum ada wishlist.</p>
            </div>
        @endif
    </div><!-- /panel-wishlist -->
@endsection

@push('styles')
    <style>
        /* Wishlist placeholder - same as product detail but fitting card */
        .prod-img-wrap2 {
            position: relative;

        }
        .prod-img-wrap2 .img-placeholder-lg {
            width: 100%;
            min-height: 180px;
            aspect-ratio: 1/1;
            background: #111;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.12);
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            user-select: none;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Remove item from wishlist - product level (nama unik agar tidak tabrakan dengan layout)
        function removeWishlistItem(produkId, btn) {
            if (typeof Swal === 'undefined') {
                if (!confirm('Hapus dari wishlist?')) return;
            } else {
                Swal.fire({
                    title: 'Hapus dari wishlist?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal'
                }).then(function(result) {
                    if (!result.isConfirmed) return;
                    doRemoveWishlist(produkId, btn);
                });
                return;
            }
            doRemoveWishlist(produkId, btn);
        }

        function doRemoveWishlist(produkId, btn) {
            var card = btn.closest('.prod-card');
            fetch('/wishlist/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify({ produk_id: produkId })
            })
                .then(function(r) { return r.json(); })
                .then(function(d) {
                    if (d.ok && d.wishlisted === false) {
                        if (card) {
                            card.style.transition = 'opacity 0.3s, transform 0.3s';
                            card.style.opacity = '0';
                            card.style.transform = 'scale(0.95)';
                            setTimeout(function() {
                                card.remove();
                                // Update count
                                var countEl = document.querySelector('.dash-section-head span');
                                if (countEl) {
                                    var count = parseInt(countEl.textContent.match(/\d+/)?.[0] || '0');
                                    countEl.textContent = (count - 1) + ' item tersimpan';
                                }
                                // Update sidebar badge
                                var badge = document.querySelector('a[href*="wishlist"] .dash-nav-badge');
                                if (badge) {
                                    var bCount = parseInt(badge.textContent) - 1;
                                    if (bCount <= 0) {
                                        badge.remove();
                                    } else {
                                        badge.textContent = bCount;
                                    }
                                }
                                // If no more items, reload page to show empty state
                                var remaining = document.querySelectorAll('.grid-prod .prod-card').length;
                                if (remaining === 0) {
                                    location.reload();
                                }
                            }, 300);
                        }
                    }
                })
                .catch(function(e) {
                    console.error(e);
                });
        }

        // Add to cart from wishlist
        function addToCartFromWishlist(barangId) {
            fetch('{{ route("wishlist.add-to-cart") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ barang_id: barangId, qty: 1 })
            })
                .then(function(r) { return r.json(); })
                .then(function(d) {
                    if (d.ok) {
                        var badge = document.querySelector('.dash-nav-item[href*="keranjang"] .dash-nav-badge');
                        if (badge && d.cart_count !== undefined) {
                            badge.textContent = d.cart_count;
                            badge.style.display = '';
                        }
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({ icon: 'success', title: 'Ditambahkan', text: 'Produk masuk ke keranjang.', timer: 2000, showConfirmButton: false });
                        } else {
                            alert('Produk ditambahkan ke keranjang.');
                        }
                    } else {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: d.message || 'Gagal menambahkan ke keranjang.' });
                        } else {
                            alert(d.message || 'Gagal menambahkan ke keranjang.');
                        }
                    }
                })
                .catch(function() {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan koneksi' });
                    } else {
                        alert('Terjadi kesalahan koneksi');
                    }
                });
        }
    </script>
@endpush