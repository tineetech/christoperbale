@extends('layouts.app')

@section('title', $product['name'] . ' — CHRISBALE')

@push('styles')
    <style>
        .img-placeholder-lg {
            width: 100%;
            min-height: 400px;
            aspect-ratio: 1/1;
            background: #111;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.12);
            font-size: 48px;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            user-select: none;
        }

        .img-placeholder {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            background: #111;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.15);
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            user-select: none;
        }

        .pd-thumbs {
            display: flex;
            gap: 8px;
            margin-top: 12px;
        }

        .pd-thumbs .thumb {
            width: 72px;
            height: 72px;
            border-radius: 6px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
            transition: border-color .2s;
            flex-shrink: 0;
        }

        .pd-thumbs .thumb.active {
            border-color: var(--accent);
        }

        .pd-thumbs .thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .pd-main-img {
            position: relative;
        }

        .pd-main-img #pdMainImage {
            transition: opacity .4s ease;
        }

        .pd-size-option.disabled label,
        .pd-color-option.disabled label {
            opacity: 0.3;
            cursor: not-allowed;
        }

        .pd-size-option.disabled input:checked+label,
        .pd-color-option.disabled input:checked+label {
            opacity: 0.3;
            border-color: var(--line);
        }
        .pd-color-name{font-size:12px;color:var(--ink-muted);margin-top:-10px;}
        .pd-actions{display:flex;gap:10px;margin-top:20px;}
        .pd-action-wrap{flex:1;}
        .pd-actions button{width:100%;padding:18px 20px;border-radius:var(--radius-sm);font-size:13px;font-weight:600;letter-spacing:0.05em;cursor:pointer;transition:background .2s,color .2s;text-transform:uppercase;font-family:'Inter',sans-serif;line-height:1;box-sizing:border-box;text-align:center;}
        .pd-actions .pd-add-cart{background:transparent;color:var(--accent);border:1.5px solid var(--accent);}
        .pd-actions .pd-add-cart:hover{background:var(--accent);color:#fff;}
        .pd-actions .pd-buy-now{background:var(--accent);color:#fff;border:1.5px solid var(--accent);}
        .pd-actions .pd-buy-now:hover{background:#a0780a;border-color:#a0780a;}
        .pd-actions button .btn-icon{display:inline-flex;vertical-align:middle;}
        .pd-actions button .btn-icon svg{width:16px;height:16px;}
        .pd-actions button .btn-text{margin-left:8px;}
        .pd-wishlist{margin-bottom:24px;}
        .pd-actions button:disabled{cursor:not-allowed;opacity:0.5;pointer-events:none;}
        .pd-stock{display:flex;align-items:center;gap:8px;margin-top:14px;padding:10px 14px;border-radius:10px;border:1px solid var(--line);background:rgba(46,125,50,0.05);font-size:12.5px;color:var(--ink-soft);}
        .pd-stock .pd-stock-dot{width:9px;height:9px;border-radius:50%;background:var(--green);flex-shrink:0;}
        .pd-stock.is-low{border-color:rgba(212,148,62,0.5);background:rgba(212,148,62,0.08);color:#8a6d1a;}
        .pd-stock.is-low .pd-stock-dot{background:#d4943e;}
        .pd-stock.is-out{border-color:rgba(192,57,43,0.4);background:rgba(192,57,43,0.06);color:var(--red);}
.pd-stock.is-out .pd-stock-dot{background:var(--red);}
        .pd-stock .pd-stock-strong{font-weight:700;color:inherit;}

        .pd-wishlist{display:inline-flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:16px 20px;border-radius:var(--radius-sm);font-size:13px;font-weight:600;letter-spacing:0.05em;cursor:pointer;transition:all .2s;text-transform:uppercase;font-family:'Inter',sans-serif;line-height:1;background:transparent;color:var(--ink);border:1.5px solid var(--line);user-select:none;}
        .pd-wishlist:hover{border-color:var(--accent);color:var(--accent);background:rgba(184,134,11,0.05);}
        .pd-wishlist svg{width:20px;height:20px;flex-shrink:0;transition:all .2s;}
        .pd-wishlist svg path{fill:none;stroke:currentColor;stroke-width:1.5;transition:all .2s;}
        .pd-wishlist svg.filled path{fill:var(--accent);stroke:var(--accent);}
        .pd-wishlist:disabled{cursor:not-allowed;opacity:0.5;pointer-events:none;}

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
            margin-top: 40px;
            flex-wrap: wrap;
        }
        .pagination button {
            min-width: 40px;
            height: 40px;
            padding: 0 12px;
            border: 1.5px solid var(--line);
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 500;
            color: var(--ink-soft);
            background: var(--bg-card);
            cursor: pointer;
            transition: all .2s;
            font-family: 'Inter', sans-serif;
        }
        .pagination button:hover:not(:disabled):not(.active) {
            border-color: var(--accent);
            color: var(--accent);
        }
        .pagination button.active {
            border-color: var(--accent);
            background: var(--accent);
            color: #fff;
        }
        .pagination button:disabled {
            opacity: 0.35;
            cursor: default;
        }
        .pagination .page-info {
            font-size: 13px;
            color: var(--ink-muted);
            padding: 0 8px;
            white-space: nowrap;
        }
        @media(max-width:600px) {
            .pagination button {
                min-width: 36px;
                height: 36px;
                font-size: 12px;
                padding: 0 8px;
            }
        }
</style>
@endpush

@section('content')

    <section class="pd-section">
        <div class="wrap">
            <div class="breadcrumb">
                <a href="/products">Produk</a>
                <span class="sep">/</span>
                <a href="/products/{{ strtolower($product['brand']) }}">{{ $product['brand'] }}</a>
                <span class="sep">/</span>
                <span>{{ $product['name'] }}</span>
            </div>

            <div class="pd-grid" style="margin-top:24px;">
                <div class="pd-gallery">
                    <div class="pd-main-img">
                        {{-- @if ($product['badge'])
                            <span class="badge-tag badge-{{ $product['badge'] }}">
                                @switch($product['badge'])
                                    @case('new')
                                        Baru
                                    @break

                                    @case('sale')
                                        @if ($product['old'] && $product['old'] > $product['price'])
                                            -{{ round((1 - $product['price'] / $product['old']) * 100) }}%
                                        @else
                                            Sale
                                        @endif
                                    @break

                                    @case('hot')
                                        Populer
                                    @break
                                @endswitch
                            </span>
                        @endif --}}
                        @if ($product['img'])
                            <img src="{{ $product['img'] }}" alt="{{ $product['name'] }}" id="pdMainImage">
                        @else
                            <div class="img-placeholder-lg">{{ $product['brand'] }}</div>
                        @endif
                    </div>
                    @if (!empty($product['imgs']))
                        <div class="pd-thumbs">
                            @foreach ($product['imgs'] as $i => $img)
                                <div class="thumb {{ $i === 0 ? 'active' : '' }}"><img src="{{ $img }}"
                                        alt=""></div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="pd-info-col">
                    <div class="pd-brand">{{ $product['brand'] }}</div>
                    <h1 class="pd-name">{{ $product['name'] }}</h1>

                    <div class="pd-rating">
                        <div class="stars">
                            <svg class="star-full" viewBox="0 0 24 24">
                                <polygon
                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                            </svg>
                            <svg class="star-full" viewBox="0 0 24 24">
                                <polygon
                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                            </svg>
                            <svg class="star-full" viewBox="0 0 24 24">
                                <polygon
                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                            </svg>
                            <svg class="star-full" viewBox="0 0 24 24">
                                <polygon
                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                            </svg>
                            <svg class="star-half" viewBox="0 0 24 24" style="position:relative;">
                                <polygon
                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"
                                    fill="none" stroke="var(--ink-muted)" stroke-width="1.5" />
                                <clipPath id="halfClip">
                                    <rect x="0" y="0" width="12" height="24" />
                                </clipPath>
                                <polygon
                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"
                                    fill="var(--accent)" clip-path="url(#halfClip)" />
                            </svg>
                        </div>
                        <span class="count">({{ $product['review_count'] ?? 42 }} ulasan)</span>
                    </div>

                    <div class="pd-price-row">
                        <span class="current">Rp{{ number_format($product['price'], 0, ',', '.') }}</span>
                        @if ($product['old'] && $product['old'] > $product['price'])
                            <span class="old">Rp{{ number_format($product['old'], 0, ',', '.') }}</span>
                            <span class="discount">-{{ round((1 - $product['price'] / $product['old']) * 100) }}%</span>
                        @endif
                    </div>

                    <div class="pd-divider"></div>

                    @if (!empty($product['sizes']))
                        <div class="pd-label">Pilih Ukuran</div>
                        <div class="pd-sizes">
                            @foreach ($product['sizes'] as $size)
                                <div class="pd-size-option {{ !$size['available'] ? 'disabled' : '' }}">
                                    <input type="radio" name="size" id="size-{{ $loop->index }}"
                                        value="{{ $size['value'] }}" {{ $loop->first ? 'checked' : '' }}
                                        {{ !$size['available'] ? 'disabled' : '' }}>
                                    <label for="size-{{ $loop->index }}">{{ $size['value'] }}</label>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if (!empty($product['colors']))
                        <div class="pd-label">Pilih Warna</div>
                        <div class="pd-colors">
                            @foreach ($product['colors'] as $color)
                                <div class="pd-color-option {{ !$color['available'] ? 'disabled' : '' }}">
                                    <input type="radio" name="color" id="color-{{ $loop->index }}"
                                        value="{{ $color['name'] }}" {{ $loop->first ? 'checked' : '' }}
                                        {{ !$color['available'] ? 'disabled' : '' }}>
                                    <label for="color-{{ $loop->index }}" style="background:{{ $color['hex'] }};"
                                        title="{{ $color['name'] }}"></label>
                                </div>
                            @endforeach
                        </div>
                        <div class="pd-color-name" id="selectedColorName">{{ $product['colors'][0]['name'] ?? '' }}</div>
                    @endif

                    <div class="pd-label " style="margin-top: 10px">Jumlah</div>
                    <div class="pd-qty">
                        <button class="qty-minus"
                            onclick="document.getElementById('qtyInput').stepDown();updateQty();">&#8722;</button>
                        <input type="number" id="qtyInput" value="1" min="1" max="99"
                            oninput="updateQty();" onchange="updateQty();">
                        <button class="qty-plus"
                            onclick="document.getElementById('qtyInput').stepUp();updateQty();">&#43;</button>
                    </div>

                    <div class="pd-stock" id="pdStockInfo">
                        <span class="pd-stock-dot"></span>
                        <span id="pdStockText">Memuat stok...</span>
                    </div>

                    <div class="pd-actions">
                        <div class="pd-action-wrap"><button class="pd-add-cart" onclick="authGuard(function(){ addToCart({{ $product['id'] ?? 'null' }}) })"><span class="btn-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg></span><span class="btn-text">Tambah ke Keranjang</span></button></div>
                        <div class="pd-action-wrap"><button class="pd-buy-now" onclick="authGuard(function(){ buyNow() })"><span class="btn-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg></span><span class="btn-text">Beli Sekarang</span></button></div>
                    </div>
                    @php $isWishlisted = $product['isWishlisted'] ?? false; @endphp
                    <button class="pd-wishlist" id="wishlistBtn" data-produk-id="{{ $product['id'] }}" onclick="authGuard(function(){ toggleWishlist() })">
                        <svg id="wishlistIcon" viewBox="0 0 24 24" class="{{ $isWishlisted ? 'filled' : '' }}">
                            <path
                                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                        </svg>
                        <span id="wishlistText">{{ $isWishlisted ? 'Di Wishlist' : 'Simpan ke Wishlist' }}</span>
                    </button>

                    @if (!empty($product['desc']))
                        <div class="pd-divider"></div>
                        <div class="pd-desc">
                            <h2>Deskripsi Produk</h2>
                            <p>{!! nl2br(e($product['desc'])) !!}</p>
                        </div>
                    @endif

                    @if (!empty($product['features']))
                        <div class="pd-features">
                            <h2>Fitur Utama</h2>
                            <ul>
                                @foreach ($product['features'] as $feature)
                                    <li>
                                        <svg viewBox="0 0 24 24">
                                            <polyline points="20 6 9 17 4 12" />
                                        </svg>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="pd-meta">
                        <dt>Brand</dt>
                        <dd>{{ $product['brand'] }}</dd>
                        <dt>Total Pembeli</dt>
                        <dd>{{ number_format($product['total_buyers'] ?? $product['review_count'], 0, ',', '.') }} orang</dd>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="produk-lainnya" style="padding-top:32px;">
        <div class="wrap">
            <div class="section-head">
                <div>
                    <h2>Produk Lainnya</h2>
                    <p>Jelajahi koleksi lainnya yang mungkin kamu suka.</p>
                </div>
            </div>

            @if ($otherProducts->count() > 0)
                <div class="grid-prod" id="otherProductGrid">
                    @foreach ($otherProducts as $rp)
                        <article class="prod-card" data-name="{{ strtolower($rp['name']) }}" data-brand="{{ $rp['brand'] }}" data-price="{{ $rp['price'] }}" onclick="window.location='/product/{{ $rp['slug'] }}'">
                            @if ($rp['badge'])
                                <span class="badge-tag badge-{{ $rp['badge'] }}">
                                    @switch($rp['badge'])
                                        @case('new')
                                            Baru
                                        @break

                                        @case('sale')
                                            @if ($rp['old'] && $rp['old'] > $rp['price'])
                                                -{{ round((1 - $rp['price'] / $rp['old']) * 100) }}%
                                            @else
                                                Sale
                                            @endif
                                        @break

                                        @case('hot')
                                            Populer
                                        @break
                                    @endswitch
                                </span>
                            @endif
                            <div class="prod-img-wrap">
                                @if ($rp['img'])
                                    <img src="{{ $rp['img'] }}" alt="{{ $rp['name'] }}" loading="lazy">
                                @else
                                    <div class="img-placeholder">{{ $rp['brand'] }}</div>
                                @endif
                                <span class="badge-brand">{{ $rp['brand'] }}</span>
                                <div class="prod-overlay"><button class="add-btn" onclick="event.stopPropagation(); window.location='/product/{{ $rp['slug'] }}'">+ Keranjang</button></div>
                            </div>
                            <div class="prod-info">
                                <h3>{{ $rp['name'] }}</h3>
                                <div class="price-row">
                                    <span class="current">Rp{{ number_format($rp['price'], 0, ',', '.') }}</span>
                                    @if ($rp['old'])
                                        <span class="old">Rp{{ number_format($rp['old'], 0, ',', '.') }}</span>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                @if ($otherProducts->hasPages())
                    <div class="pagination" id="otherPagination">
                        {{-- Prev --}}
                        @if ($otherProducts->onFirstPage())
                            <button disabled>‹</button>
                        @else
                            <a href="{{ $otherProducts->previousPageUrl() }}#produk-lainnya"><button>‹</button></a>
                        @endif

                        @php
                            $current = $otherProducts->currentPage();
                            $last = $otherProducts->lastPage();
                            $start = max(1, $current - 2);
                            $end = min($last, $current + 2);
                            if ($end - $start < 4) {
                                $start = max(1, $end - 4);
                                $end = min($last, $start + 4);
                            }
                        @endphp

                        @if ($start > 1)
                            <a href="{{ $otherProducts->url(1) }}#produk-lainnya"><button>1</button></a>
                            @if ($start > 2)
                                <span class="page-info">…</span>
                            @endif
                        @endif

                        @for ($i = $start; $i <= $end; $i++)
                            @if ($i == $current)
                                <button class="active">{{ $i }}</button>
                            @else
                                <a href="{{ $otherProducts->url($i) }}#produk-lainnya"><button>{{ $i }}</button></a>
                            @endif
                        @endfor

                        @if ($end < $last)
                            @if ($end < $last - 1)
                                <span class="page-info">…</span>
                            @endif
                            <a href="{{ $otherProducts->url($last) }}#produk-lainnya"><button>{{ $last }}</button></a>
                        @endif

                        {{-- Next --}}
                        @if ($otherProducts->hasMorePages())
                            <a href="{{ $otherProducts->nextPageUrl() }}#produk-lainnya"><button>›</button></a>
                        @else
                            <button disabled>›</button>
                        @endif
                    </div>
                @endif
            @else
                <div style="text-align:center;padding:40px 20px;color:var(--ink-muted);">
                    <p>Belum ada produk lainnya.</p>
                </div>
            @endif
        </div>
    </section>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function updateQty() {
            var i = document.getElementById('qtyInput');
            if (i.value < 1) i.value = 1;
            if (i.value > 99) i.value = 99;
            updateStockInfo();
        }

        function updateStockInfo() {
            var info = document.getElementById('pdStockInfo');
            var text = document.getElementById('pdStockText');
            var addBtn = document.querySelector('.pd-add-cart');
            var buyBtn = document.querySelector('.pd-buy-now');
            var qty = parseInt(document.getElementById('qtyInput').value, 10) || 1;
            var match = getSelectedVariant();

            if (!match) {
                if (info) info.className = 'pd-stock is-out';
                if (text) text.innerHTML = 'Pilih ukuran & warna untuk melihat stok.';
                if (addBtn) addBtn.disabled = true;
                if (buyBtn) buyBtn.disabled = true;
                return;
            }

            var stok = match.stok;
            if (stok <= 0) {
                if (info) info.className = 'pd-stock is-out';
                if (text) text.innerHTML = 'Stok produk ini <span class="pd-stock-strong">habis</span>.';
            } else if (qty > stok) {
                if (info) info.className = 'pd-stock is-low';
                if (text) text.innerHTML = 'Stok tersedia hanya <span class="pd-stock-strong">' + stok + '</span>, jumlah dipesan ' + qty + ' melebihi stok.';
            } else {
                if (info) info.className = 'pd-stock';
                if (text) text.innerHTML = '<span class="pd-stock-strong">' + stok + '</span> Stok tersedia.';
            }

            var insufficient = !match || stok < qty;
            if (addBtn) addBtn.disabled = insufficient;
            if (buyBtn) buyBtn.disabled = insufficient;
        }
        document.querySelectorAll('.pd-thumbs .thumb').forEach(function(t) {
            t.addEventListener('click', function() {
                document.querySelectorAll('.pd-thumbs .thumb').forEach(function(x) {
                    x.classList.remove('active')
                });
                this.classList.add('active');
                var img = document.getElementById('pdMainImage');
                img.style.opacity = 0;
                setTimeout(function() {
                    img.src = t.querySelector('img').src;
                    img.style.opacity = 1;
                }, 200);
                resetSlide();
            });
        });

        var slideTimer;
        function startSlide() {
            var thumbs = document.querySelectorAll('.pd-thumbs .thumb');
            if (thumbs.length < 2) return;
            slideTimer = setInterval(function() {
                var active = document.querySelector('.pd-thumbs .thumb.active');
                var next = active.nextElementSibling || thumbs[0];
                next.click();
            }, 5000);
        }
        function resetSlide() {
            clearInterval(slideTimer);
            startSlide();
        }
        startSlide();
        var isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
        function authGuard(cb) {
            if (!isLoggedIn) { window.location.href = '/login'; return; }
            cb();
        }
        document.querySelectorAll('.pd-size-option input').forEach(function(r) {
            r.addEventListener('change', function() {
                document.querySelectorAll('.pd-size-option input').forEach(function(x) {
                    if (x.checked) x.closest('.pd-size-option').querySelector('label').style
                        .borderColor = '';
                });
                if (this.checked) this.closest('.pd-size-option').querySelector('label').style.borderColor =
                    'var(--accent)';
                updateStockInfo();
            });
        });
        document.querySelectorAll('.pd-color-option input').forEach(function(r) {
            r.addEventListener('change', function() {
                var name = this.value;
                document.getElementById('selectedColorName').textContent = name;
                updateStockInfo();
            });
        });

        (function() {
            var params = new URLSearchParams(window.location.search);
            var sizeVal = params.get('size');
            var colorVal = params.get('color');
            if (sizeVal) {
                var sizeInput = document.querySelector('input[name=size][value="' + sizeVal.replace(/"/g, '\\"') + '"]');
                if (sizeInput) { sizeInput.checked = true; sizeInput.dispatchEvent(new Event('change')); }
            }
            if (colorVal) {
                var colorInput = document.querySelector('input[name=color][value="' + colorVal.replace(/"/g, '\\"') + '"]');
                if (colorInput) { colorInput.checked = true; colorInput.dispatchEvent(new Event('change')); }
            }
        })();

        updateStockInfo();

        function getSelectedVariant() {
            var sizeInput = document.querySelector('input[name=size]:checked');
            var colorInput = document.querySelector('input[name=color]:checked');
            var size = sizeInput ? sizeInput.value : '';
            var color = colorInput ? colorInput.value : '';
            var variants = @json($product['variants'] ?? []);
            var match = null;
            if (size && color) {
                match = variants.find(function(v) { return v.size === size && v.color === color; });
            } else if (size) {
                match = variants.find(function(v) { return v.size === size; });
            } else if (color) {
                match = variants.find(function(v) { return v.color === color; });
            }
            return match;
        }

        function showSwal(icon, title, text) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: icon, title: title, text: text });
            } else {
                alert(title + ': ' + text);
            }
        }

        function buyNow() {
            var qty = parseInt(document.getElementById('qtyInput').value, 10) || 1;
            var match = getSelectedVariant();
            if (!match) {
                showSwal('warning', 'Varian tidak ditemukan', 'Silakan pilih ukuran dan warna yang tersedia.');
                return;
            }
            if (match.stok < qty) {
                showSwal('error', 'Stok Tidak Cukup', 'Stok tersedia: ' + match.stok + '. Jumlah yang dipesan: ' + qty + '.');
                return;
            }
            var params = new URLSearchParams();
            params.set('nama_barang', match.nama);
            params.set('qty', qty);
            window.location.href = '/checkout?' + params.toString();
        }

        function addToCart(produkId) {
            var btn = document.querySelector('.pd-add-cart');
            var orig = btn.textContent;
            var qty = parseInt(document.getElementById('qtyInput').value, 10) || 1;
            var match = getSelectedVariant();
            if (!match) {
                showSwal('warning', 'Varian tidak ditemukan', 'Silakan pilih ukuran dan warna yang tersedia.');
                return;
            }
            if (match.stok < qty) {
                showSwal('error', 'Stok Tidak Cukup', 'Stok tersedia: ' + match.stok + '. Jumlah yang dipesan: ' + qty + '.');
                return;
            }
            btn.disabled = true;
            btn.textContent = 'Memproses...';
            var sizeInput = document.querySelector('input[name=size]:checked');
            var colorInput = document.querySelector('input[name=color]:checked');
            fetch('/cart/add', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                body: JSON.stringify({ produk_id: produkId, size: sizeInput ? sizeInput.value : '', color: colorInput ? colorInput.value : '', qty: qty })
            }).then(function(r) {
                if (!r.ok) return r.json().then(function(d) { throw new Error(d.message || 'Gagal'); });
                return r.json();
            }).then(function(d) {
                if (d.ok) {
                    if (d.cart_count !== undefined) {
                        var badge = document.getElementById('cartCountBadge');
                        if (badge) {
                            badge.textContent = d.cart_count;
                            badge.style.display = d.cart_count > 0 ? '' : 'none';
                        }
                    }
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Produk ditambahkan ke keranjang.', timer: 2000, showConfirmButton: false });
                    } else {
                        alert('Produk ditambahkan ke keranjang.');
                    }
                }
            }).catch(function(e) {
                showSwal('error', 'Gagal', e.message);
            }).finally(function() {
                btn.disabled = false;
                btn.textContent = orig;
            });
        }

        // Toggle wishlist - product level (cover all variants)
        function toggleWishlist() {
            var btn = document.getElementById('wishlistBtn');
            var icon = document.getElementById('wishlistIcon');
            var text = document.getElementById('wishlistText');
            var produkId = parseInt(btn.dataset.produkId, 10);
            var match = getSelectedVariant();
            var barangId = match && match.barang_id ? match.barang_id : null;
            var isCurrentlyWishlisted = icon.classList.contains('filled');

            btn.disabled = true;
            var origText = text.textContent;
            text.textContent = isCurrentlyWishlisted ? 'Menghapus...' : 'Menyimpan...';

            fetch('/wishlist/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify({ produk_id: produkId, barang_id: barangId })
            })
                .then(function(r) { return r.json(); })
                .then(function(d) {
                    if (d.ok) {
                        if (d.wishlisted) {
                            icon.classList.add('filled');
                            text.textContent = 'Di Wishlist';
                            showSwal('success', 'Berhasil', d.message);
                        } else {
                            icon.classList.remove('filled');
                            text.textContent = 'Simpan ke Wishlist';
                            showSwal('success', 'Berhasil', d.message);
                        }
                    } else {
                        showSwal('error', 'Gagal', d.message);
                    }
                })
                .catch(function(e) {
                    showSwal('error', 'Error', 'Terjadi kesalahan');
                })
                .finally(function() {
                    btn.disabled = false;
                    if (!icon.classList.contains('filled') && text.textContent === 'Menyimpan...') {
                        text.textContent = origText;
                    }
                });
        }

        // Wishlist is product-level - no variant matching needed
        // Icon already set correctly on page load via PHP isWishlisted
        // No need to update on variant change
    </script>
@endpush
