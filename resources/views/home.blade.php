@extends('layouts.app')

@section('title', 'CHRISBALE — Premium Footwear Marketplace')

@push('styles')
<style>
/* Scroll Fade-in Animations */
.reveal {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.7s ease-out, transform 0.7s ease-out;
}
.reveal.visible {
    opacity: 1;
    transform: translateY(0);
}

/* Staggered delay classes */
.reveal-delay-1 { transition-delay: 0.1s; }
.reveal-delay-2 { transition-delay: 0.2s; }
.reveal-delay-3 { transition-delay: 0.3s; }
.reveal-delay-4 { transition-delay: 0.4s; }
.reveal-delay-5 { transition-delay: 0.5s; }
.reveal-delay-6 { transition-delay: 0.6s; }

/* Fade-in from left/right variants */
.reveal-left {
    opacity: 0;
    transform: translateX(-40px);
    transition: opacity 0.7s ease-out, transform 0.7s ease-out;
}
.reveal-left.visible { opacity: 1; transform: translateX(0); }

.reveal-right {
    opacity: 0;
    transform: translateX(40px);
    transition: opacity 0.7s ease-out, transform 0.7s ease-out;
}
.reveal-right.visible { opacity: 1; transform: translateX(0); }

/* Scale reveal for cards */
.reveal-scale {
    opacity: 0;
    transform: scale(0.95);
    transition: opacity 0.6s ease-out, transform 0.6s ease-out;
}
.reveal-scale.visible { opacity: 1; transform: scale(1); }

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    .reveal, .reveal-left, .reveal-right, .reveal-scale {
        opacity: 1;
        transform: none;
        transition: none;
    }
}

/* Modern Testimonial Rating Summary */
.testi-rating-summary {
    display: grid;
    grid-template-columns: auto 1fr;
    gap: 28px;
    align-items: start;
    padding: 8px 0;
}
.trs-score {
    font-family: 'Inter', sans-serif;
    font-size: clamp(44px, 5vw, 56px);
    font-weight: 800;
    letter-spacing: -0.02em;
    color: var(--ink);
    line-height: 1;
    display: flex;
    align-items: baseline;
    gap: 4px;
}
.trs-score sup {
    font-size: 0.45em;
    font-weight: 600;
    color: var(--accent);
}
.trs-right {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.trs-stars {
    display: flex;
    gap: 4px;
}
.trs-stars svg {
    width: 16px;
    height: 16px;
    fill: var(--accent);
    color: var(--accent);
}
.trs-count {
    font-size: 13px;
    font-weight: 500;
    color: var(--ink-muted);
    letter-spacing: 0.01em;
}
.trs-bars {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 6px;
}
.trs-bar-row {
    display: grid;
    grid-template-columns: 36px 1fr 42px;
    align-items: center;
    gap: 14px;
    font-size: 12px;
    font-weight: 500;
    color: var(--ink-muted);
}
.trs-bar-row > span:first-child {
    text-align: right;
    font-variant-numeric: tabular-nums;
    color: var(--ink);
}
.trs-bar-row > span:last-child {
    text-align: right;
    font-variant-numeric: tabular-nums;
    color: var(--accent);
    font-weight: 600;
}
.trs-bar {
    height: 6px;
    background: rgba(26, 26, 26, 0.06);
    border-radius: 99px;
    overflow: hidden;
    position: relative;
}
.trs-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--accent), #e8c56d);
    border-radius: 99px;
    width: 0;
    transition: width 1.2s cubic-bezier(0.22, 1, 0.36, 1);
    position: relative;
}
.trs-bar-fill::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    animation: shimmer 2s infinite;
}
@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}
.reveal-scale .trs-bar-fill {
    width: var(--target-width, 0);
}

/* Responsive */
@media (max-width: 640px) {
    .testi-rating-summary {
        grid-template-columns: 1fr;
        gap: 18px;
        padding: 0;
        text-align: center;
    }
    .trs-score {
        justify-content: center;
    }
    .trs-stars {
        justify-content: center;
    }
    .trs-bar-row {
        grid-template-columns: 30px 1fr 38px;
        gap: 10px;
    }
}

/* Empty product grid */
.grid-empty{
    padding:64px 32px;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    gap:20px;
    text-align:center;
    background:linear-gradient(180deg, var(--bg-card) 0%, var(--bg) 100%);
    border:1px dashed var(--line);
    border-radius:var(--radius);
}
.grid-empty .empty-icon{
    width:92px;
    height:auto;
}
.grid-empty-text{
    display:flex;
    flex-direction:column;
    gap:8px;
    max-width:360px;
}
.grid-empty-text h4{
    font-family:'Playfair Display',serif;
    font-size:18px;
    font-weight:600;
    color:var(--ink);
    margin:0;
}
.grid-empty-text p{
    margin:0;
    font-size:13.5px;
    color:var(--ink-muted);
    line-height:1.7;
}
.grid-empty-btn{
    margin-top:4px;
    padding:11px 26px !important;
    font-size:11.5px !important;
}

/* Product image placeholder (no photo) */
.prod-img-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #141414;
    color: rgba(255, 255, 255, .45);
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
}
</style>
@endpush

@section('content')

    <!-- HERO CAROUSEL -->
    <section class="hero" id="heroCarousel">
        <div class="hero-track" id="heroTrack">
            @forelse ($banners as $index => $banner)
                <div class="hero-slide{{ $index === 0 ? ' is-active' : '' }}">
                    <div class="hero-bg-full">
                        <img src="{{ env('BE_URL') . '/storage/' . $banner->gambar }}" alt="{{ $banner->title }}">
                    </div>
                    <div class="hero-overlay">
                        <h1>{{ $banner->title }}</h1>
                        @if ($banner->catatan)
                            <p>{{ $banner->catatan }}</p>
                        @endif
                        <div class="hero-actions">
                            <a href="#shop" class="btn-primary">Beli Sekarang <svg width="13" height="13"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M5 12h14M13 6l6 6-6 6" />
                                </svg></a>
                            <a href="#sale" class="btn-outline-hero">Jelajahi</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="hero-slide is-active">
                    <div class="hero-bg-full">
                        <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=1400&q=80&auto=format&fit=crop"
                            alt="CHRISBALE Sneaker">
                    </div>
                    <div class="hero-overlay">
                        <h1>Kenyamanan Bertemu<br>Elegansi Urban</h1>
                        <p>Temukan koleksi alas kaki terbaru yang dikurasi untuk langkah modern.</p>
                        <div class="hero-actions">
                            <a href="#shop" class="btn-primary">Beli Sekarang <svg width="13" height="13"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M5 12h14M13 6l6 6-6 6" />
                                </svg></a>
                            <a href="#sale" class="btn-outline-hero">Jelajahi</a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <button class="carousel-arrow prev" id="heroPrev" aria-label="Slide sebelumnya">
            <svg viewBox="0 0 24 24">
                <polyline points="15 18 9 12 15 6" />
            </svg>
        </button>
        <button class="carousel-arrow next" id="heroNext" aria-label="Slide berikutnya">
            <svg viewBox="0 0 24 24">
                <polyline points="9 6 15 12 9 18" />
            </svg>
        </button>

        <div class="carousel-dots" id="heroDots">
            @forelse ($banners as $index => $banner)
                <button class="carousel-dot{{ $index === 0 ? ' active' : '' }}" data-index="{{ $index }}"
                    aria-label="Slide {{ $index + 1 }}"></button>
            @empty
                <button class="carousel-dot active" data-index="0" aria-label="Slide 1"></button>
            @endforelse
        </div>

        <div class="carousel-counter" id="heroCounter">01 / {{ str_pad(max(1, count($banners)), 2, '0', STR_PAD_LEFT) }}</div>
        {{-- <div class="carousel-progress" id="heroProgress"></div> --}}
    </section>

    <!-- MARQUEE -->
    {{-- <div class="marquee">
        <div class="marquee-inner marquee-animate">
            <span>Barang Baru Setiap Hari</span><span class="sep">✦</span>
            <span>Gratis Ongkir di Atas Rp2 Juta</span><span class="sep">✦</span>
            <span>Retur Mudah 30 Hari</span><span class="sep">✦</span>
            <span>Kualitas Premium Terjamin</span><span class="sep">✦</span>
            <span>Eksklusif CHRISBALE</span><span class="sep">✦</span>
            <span>Barang Baru Setiap Hari</span><span class="sep">✦</span>
            <span>Gratis Ongkir di Atas Rp2 Juta</span><span class="sep">✦</span>
            <span>Retur Mudah 30 Hari</span><span class="sep">✦</span>
            <span>Kualitas Premium Terjamin</span><span class="sep">✦</span>
            <span>Eksklusif CHRISBALE</span><span class="sep">✦</span>
        </div>
    </div> --}}

    <!-- PRODUCT GRID — Best Sellers -->
    <section class="section reveal" id="shop">
        <div class="wrap">
            <div class="section-head reveal-left">
                <div class="section-head-left">
                    <span class="eyebrow">Terlaris</span>
                    <h2>Paling Populer Minggu Ini</h2>
                    <p>Pilihan terbaik dari koleksi terbaru kami — disukai ribuan orang.</p>
                </div>
                <a href="/products" class="section-link reveal-right">Lihat Semua</a>
            </div>
            @if ($popularProducts->isEmpty())
    <div class="grid-empty">
        <svg class="empty-icon" viewBox="0 0 120 110" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <ellipse cx="60" cy="98" rx="34" ry="5.5" fill="var(--line-soft)" />
            <path d="M42 30h36v28c0 9.9-8.1 18-18 18s-18-8.1-18-18V30z" fill="none" stroke="var(--accent)" stroke-width="2.4" stroke-linejoin="round" />
            <path d="M42 34H30a8 8 0 000 16h12" fill="none" stroke="var(--accent)" stroke-width="2.4" stroke-linecap="round" />
            <path d="M78 34h12a8 8 0 010 16H78" fill="none" stroke="var(--accent)" stroke-width="2.4" stroke-linecap="round" />
            <rect x="54" y="76" width="12" height="10" fill="var(--accent-light)" />
            <path d="M44 92h32l-4 8H48z" fill="var(--accent)" />
            <path d="M60 38l3.4 6.9 7.6 1.1-5.5 5.4 1.3 7.6-6.8-3.6-6.8 3.6 1.3-7.6-5.5-5.4 7.6-1.1z" fill="var(--accent-light)" />
            <circle cx="24" cy="26" r="3" fill="var(--accent-light)" opacity=".7" />
            <circle cx="98" cy="60" r="2.4" fill="var(--accent)" opacity=".6" />
        </svg>
        <div class="grid-empty-text">
            <h4>Belum Ada Produk Terlaris</h4>
            <p>Produk terlaris akan tampil di sini begitu data penjualan terkumpul. Sambil menunggu, jelajahi katalog lengkap kami.</p>
        </div>
        <a href="/products" class="btn-outline-light grid-empty-btn">Lihat Semua Produk</a>
    </div>
@else
                <div class="grid-prod">
                    @foreach ($popularProducts as $product)
                        <article class="prod-card reveal-scale reveal-delay-{{ min($loop->iteration, 6) }}"
                            onclick="window.location='/product/{{ $product->slug }}'">
                            <span class="badge-tag badge-hot">Populer</span>
                            <div class="prod-img-wrap">
                                @if ($product->fotoUtama)
                                    <img src="{{ env('BE_URL') . '/storage/' . $product->fotoUtama->foto }}"
                                        alt="{{ $product->nama_produk }}" loading="lazy">
                                @else
                                    <div class="prod-img-placeholder">{{ $product->brand->nama_brand ?? 'N/A' }}</div>
                                @endif
                                <div class="prod-overlay"><button class="add-btn" onclick="event.stopPropagation()">+
                                        Keranjang</button></div>
                            </div>
                            <div class="prod-info">
                                <h3>{{ $product->nama_produk }}</h3>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- NEW ARRIVALS -->
    <section class="section reveal" style="padding-top:0;" id="new">
        <div class="wrap">
            <div class="section-head reveal-left">
                <div class="section-head-left">
                    <span class="eyebrow">Barang Baru</span>
                    <h2>Fresh Drops Minggu Ini</h2>
                    <p>Jadilah yang pertama mendapatkan gaya terbaru.</p>
                </div>
                <a href="/products" class="section-link reveal-right">Beli Baru</a>
            </div>
            @if ($newProducts->isEmpty())
    <div class="grid-empty">
        <svg class="empty-icon" viewBox="0 0 120 110" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <ellipse cx="60" cy="99" rx="34" ry="5.5" fill="var(--line-soft)" />
            <path d="M28 46l32 13 32-13v34a3 3 0 01-1.9 2.8L60 98 29.9 82.8A3 3 0 0128 80z" fill="var(--accent)" opacity=".14" />
            <path d="M28 46l32 13 32-13" fill="none" stroke="var(--accent)" stroke-width="2.4" stroke-linejoin="round" />
            <path d="M28 46l32 13 32-13v34a3 3 0 01-1.9 2.8L60 98 29.9 82.8A3 3 0 0128 80z" fill="none" stroke="var(--accent)" stroke-width="2.4" stroke-linejoin="round" />
            <path d="M60 59v39" stroke="var(--accent)" stroke-width="2" opacity=".5" />
            <path d="M60 20l3.2 8.2L72 31l-8.8 3.2L60 42l-3.2-7.8L48 31l8.8-2.8z" fill="var(--accent-light)" />
            <circle cx="88" cy="20" r="3" fill="var(--accent)" opacity=".55" />
            <circle cx="34" cy="18" r="2.2" fill="var(--accent-light)" opacity=".8" />
        </svg>
        <div class="grid-empty-text">
            <h4>Belum Ada Barang Baru</h4>
            <p>Koleksi terbaru kami sedang dalam persiapan. Pantau halaman ini agar tidak ketinggalan fresh drop selanjutnya.</p>
        </div>
        <a href="/products" class="btn-outline-light grid-empty-btn">Jelajahi Koleksi</a>
    </div>
@else
                <div class="grid-prod">
                    @foreach ($newProducts as $product)
                        <article class="prod-card reveal-scale reveal-delay-{{ min($loop->iteration, 6) }}"
                            onclick="window.location='/product/{{ $product->slug }}'">
                            <span class="badge-tag badge-new">Baru</span>
                            <div class="prod-img-wrap">
                                @if ($product->fotoUtama)
                                    <img src="{{ env('BE_URL') . '/storage/' . $product->fotoUtama->foto }}"
                                        alt="{{ $product->nama_produk }}" loading="lazy">
                                @else
                                    <div class="prod-img-placeholder">{{ $product->brand->nama_brand ?? 'N/A' }}</div>
                                @endif
                                <div class="prod-overlay"><button class="add-btn" onclick="event.stopPropagation()">+
                                        Keranjang</button></div>
                            </div>
                            <div class="prod-info">
                                <h3>{{ $product->nama_produk }}</h3>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- FEATURED BANNER -->
    <section class="section reveal" style="padding-top:0;" id="sale">
        <div class="wrap">
            <div class="featured-banner reveal-left">
                <div class="featured-img-side reveal-scale reveal-delay-1">
                    <img src="https://down-id.img.susercontent.com/file/id-11134207-7r98o-lzyg2optyrvx4e?w=900&q=80&auto=format&fit=crop"
                        alt="CHRISBALE Signature Collection">
                </div>
                <div class="featured-text reveal-right reveal-delay-2">
                    <span class="tag">Edisi Terbatas</span>
                    <h2>CHRISBALE x Agatha<br>Signature Collection</h2>
                    <p>Sneaker premium buatan tangan dengan kulit Italia full-grain, insole memory foam, dan emblem emas
                        khas kami. Hanya 500 pasang di seluruh dunia.</p>
                    <div class="featured-meta">
                        <div class="featured-meta-item reveal-scale reveal-delay-3">
                            <div class="fm-num">500</div>
                            <div class="fm-label">Pasang Saja</div>
                        </div>
                        <div class="featured-meta-item reveal-scale reveal-delay-4">
                            <div class="fm-num">100%</div>
                            <div class="fm-label">Kulit Asli</div>
                        </div>
                        <div class="featured-meta-item reveal-scale reveal-delay-5">
                            <div class="fm-num">2th</div>
                            <div class="fm-label">Garansi</div>
                        </div>
                    </div>
                    <a href="#" class="btn-primary reveal-right reveal-delay-6" style="width:fit-content;">
                        Beli Koleksi
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <path d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                    </a>
                </div>
            </div>
            <div class="featured-strip reveal">
                <div class="strip-item reveal-scale reveal-delay-1">
                    <div class="strip-item-img"><img
                            src="https://down-id.img.susercontent.com/file/id-11134207-7rasl-m2luwjdu2ais83?w=500&q=80&auto=format&fit=crop"
                            alt="Gold Edition" loading="lazy"></div>
                    <div class="strip-item-info">
                        <h4>Gold Edition</h4><span></span>
                    </div>
                </div>
                <div class="strip-item reveal-scale reveal-delay-2">
                    <div class="strip-item-img"><img
                            src="https://down-id.img.susercontent.com/file/id-11134207-7r98w-lzyg2optuk6lc4?w=500&q=80&auto=format&fit=crop"
                            alt="Midnight Black" loading="lazy"></div>
                    <div class="strip-item-info">
                        <h4>Midnight Black</h4><span></span>
                    </div>
                </div>
                <div class="strip-item reveal-scale reveal-delay-3">
                    <div class="strip-item-img"><img
                            src="https://down-id.img.susercontent.com/file/id-11134207-822wp-mn7f6ilzjkzl24?w=500&q=80&auto=format&fit=crop"
                            alt="Cream Canvas" loading="lazy"></div>
                    <div class="strip-item-info">
                        <h4>Cream Canvas</h4><span></span>
                    </div>
                </div>
                <div class="strip-item reveal-scale reveal-delay-4">
                    <div class="strip-item-img"><img
                            src="https://down-id.img.susercontent.com/file/id-11134207-822wh-mn7i7z85dr7k70?w=500&q=80&auto=format&fit=crop"
                            alt="Tan Chelsea" loading="lazy"></div>
                    <div class="strip-item-info">
                        <h4>Rose</h4><span></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS — Redesigned -->
    <section class="section testi-section reveal" style="padding-top:0;" id="testimonials">
        <div class="wrap">

            <!-- Header + Overall Rating -->
            <div class="testi-header reveal-left">
                <div class="testi-header-left">
                    <span class="eyebrow">Ulasan Pelanggan</span>
                    <h2>Apa Kata Mereka</h2>
                    <p class="testi-subhead">Lebih dari 2.400 pembeli telah memilih CHRISBALE sebagai alas kaki premium pilihan mereka.</p>
                </div>
                <div class="testi-rating-summary reveal-right">
                    <div class="trs-score">4<sup>.9</sup></div>
                    <div class="trs-right">
                        <div class="trs-stars">
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        </div>
                        <span class="trs-count">dari 2.418 ulasan</span>
                        <div class="trs-bars">
                            <div class="trs-bar-row reveal-scale reveal-delay-1" style="--target-width: 87%"><span>5★</span><div class="trs-bar"><div class="trs-bar-fill"></div></div><span>87%</span></div>
                            <div class="trs-bar-row reveal-scale reveal-delay-2" style="--target-width: 9%"><span>4★</span><div class="trs-bar"><div class="trs-bar-fill"></div></div><span>9%</span></div>
                            <div class="trs-bar-row reveal-scale reveal-delay-3" style="--target-width: 3%"><span>3★</span><div class="trs-bar"><div class="trs-bar-fill"></div></div><span>3%</span></div>
                            <div class="trs-bar-row reveal-scale reveal-delay-4" style="--target-width: 1%"><span>2★</span><div class="trs-bar"><div class="trs-bar-fill"></div></div><span>1%</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cards Carousel -->
            <div class="testi-carousel-wrap reveal">
                <div class="testi-viewport">
                    <div class="testi-track" id="testiTrack">

                    <!-- Card 1 -->
                    <article class="testi-card-v2 reveal-scale reveal-delay-1">
                        <div class="tc-top">
                            <div class="tc-avatar" style="--av-bg:#D4A843;--av-text:#5A3800;">AM</div>
                            <div class="tc-meta">
                                <span class="tc-name">Alfareza M.</span>
                                <span class="tc-location">Jakarta Selatan</span>
                            </div>
                            <div class="tc-verified" title="Pembeli Terverifikasi">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                Terverifikasi
                            </div>
                        </div>
                        <div class="tc-stars">
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        </div>
                        <blockquote class="tc-quote">"Kualitasnya melampaui ekspektasi saya. Urban Runner terasa sangat ringan tapi solnya kuat banget. Sudah 4 bulan dipakai tiap hari, kondisi masih seperti baru."</blockquote>
                        <div class="tc-product-tag">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                            CHRISBALE Urban Runner
                        </div>
                        <div class="tc-date">12 Juli 2026</div>
                    </article>

                    <!-- Card 2 -->
                    <article class="testi-card-v2 reveal-scale reveal-delay-2">
                        <div class="tc-top">
                            <div class="tc-avatar" style="--av-bg:#2E7D32;--av-text:#fff;">SJ</div>
                            <div class="tc-meta">
                                <span class="tc-name">Sarah J.</span>
                                <span class="tc-location">Bandung</span>
                            </div>
                            <div class="tc-verified">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                Terverifikasi
                            </div>
                        </div>
                        <div class="tc-stars">
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        </div>
                        <blockquote class="tc-quote">"Saya pakai Gold Leather Loafers di hari pernikahan saya. Semua tamu menanyakan sepatu ini! Craftsmanship-nya luar biasa, terasa mewah di setiap detail jahitannya."</blockquote>
                        <div class="tc-product-tag">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                            Gold Leather Loafer
                        </div>
                        <div class="tc-date">28 Juni 2026</div>
                    </article>

                    <!-- Card 3 -->
                    <article class="testi-card-v2 reveal-scale reveal-delay-3">
                        <div class="tc-top">
                            <div class="tc-avatar" style="--av-bg:#1A1A2E;--av-text:#D4A843;">MT</div>
                            <div class="tc-meta">
                                <span class="tc-name">Marcus T.</span>
                                <span class="tc-location">Surabaya</span>
                            </div>
                            <div class="tc-verified">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                Terverifikasi
                            </div>
                        </div>
                        <div class="tc-stars">
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        </div>
                        <blockquote class="tc-quote">"Pengiriman sampai dalam 2 hari. Combat Boots-nya langsung nyaman dipakai tanpa perlu break-in. Bahan kulitnya premium, tidak bau, dan anti air. Worth every rupiah!"</blockquote>
                        <div class="tc-product-tag">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                            Combat Boot — Hitam
                        </div>
                        <div class="tc-date">5 Agustus 2026</div>
                    </article>

                    <!-- Card 4 -->
                    <article class="testi-card-v2 reveal-scale reveal-delay-4">
                        <div class="tc-top">
                            <div class="tc-avatar" style="--av-bg:#C0392B;--av-text:#fff;">DK</div>
                            <div class="tc-meta">
                                <span class="tc-name">Diana K.</span>
                                <span class="tc-location">Yogyakarta</span>
                            </div>
                            <div class="tc-verified">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                Terverifikasi
                            </div>
                        </div>
                        <div class="tc-stars">
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        </div>
                        <blockquote class="tc-quote">"Akhirnya brand lokal yang kualitasnya tidak kalah dengan merek internasional! Classic Slip-On-nya jadi andalan ke kantor setiap hari. Ringan, elegan, dan tidak bikin kaki pegal."</blockquote>
                        <div class="tc-product-tag">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                            Classic Slip-On Putih
                        </div>
                        <div class="tc-date">19 Juli 2026</div>
                    </article>

                    <!-- Card 5 -->
                    <article class="testi-card-v2 reveal-scale reveal-delay-5">
                        <div class="tc-top">
                            <div class="tc-avatar" style="--av-bg:#5C3317;--av-text:#D4A843;">RP</div>
                            <div class="tc-meta">
                                <span class="tc-name">Rizky P.</span>
                                <span class="tc-location">Medan</span>
                            </div>
                            <div class="tc-verified">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                Terverifikasi
                            </div>
                        </div>
                        <div class="tc-stars">
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <svg viewBox="0 0 24 24" style="opacity:.35"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        </div>
                        <blockquote class="tc-quote">"Tan Chelsea Boot-nya keren banget buat mix & match outfit formal maupun kasual. Satu-satunya masukan: box packaging bisa lebih premium lagi. Tapi sepatunya sendiri 10/10!"</blockquote>
                        <div class="tc-product-tag">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                            Tan Chelsea Boot
                        </div>
                        <div class="tc-date">1 Agustus 2026</div>
                    </article>

                </div><!-- /.testi-track -->
                </div><!-- /.testi-viewport -->

                <!-- Nav Arrows -->
                <button class="testi-arrow testi-prev" id="testiPrev" aria-label="Ulasan sebelumnya">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                </button>
                <button class="testi-arrow testi-next" id="testiNext" aria-label="Ulasan berikutnya">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 6 15 12 9 18"/></svg>
                </button>
            </div><!-- /.testi-carousel-wrap -->

            <!-- Dots -->
            <div class="testi-dots" id="testiDots">
                <button class="testi-dot active" data-testi-index="0" aria-label="Ulasan 1"></button>
                <button class="testi-dot" data-testi-index="1" aria-label="Ulasan 2"></button>
                <button class="testi-dot" data-testi-index="2" aria-label="Ulasan 3"></button>
                <button class="testi-dot" data-testi-index="3" aria-label="Ulasan 4"></button>
                <button class="testi-dot" data-testi-index="4" aria-label="Ulasan 5"></button>
            </div>

        </div>
    </section>

<!-- CTA — Buy Now Support Section -->
    <section class="section cta-buy-section reveal" id="cta-beli">
        <div class="wrap">
            <div class="cta-buy-inner">

                <!-- Left: Copy -->
                <div class="cta-buy-copy reveal-left">
                    <span class="eyebrow" style="color:var(--accent-light);">Koleksi Terbaru 2026</span>
                    <h2 class="cta-buy-title">Langkah Pertama Menuju<br>Gaya Hidup Premium</h2>
                    <p class="cta-buy-desc">Setiap pasang CHRISBALE dibuat dengan bahan terbaik, dikurasi oleh pengrajin berpengalaman, dan dikirim langsung ke pintu Anda. Jangan tunggu kehabisan.</p>

                    <!-- Trust Chips -->
                    <div class="cta-trust-chips">
                        <div class="cta-chip reveal-scale reveal-delay-1">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                            Gratis Ongkir > Rp2 Juta
                        </div>
                        <div class="cta-chip reveal-scale reveal-delay-2">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                            Retur Mudah 30 Hari
                        </div>
                        <div class="cta-chip reveal-scale reveal-delay-3">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            Garansi 2 Tahun
                        </div>
                        <div class="cta-chip reveal-scale reveal-delay-4">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                            Bayar Aman & Terenkripsi
                        </div>
                    </div>

                    <!-- CTAs -->
                    <div class="cta-buy-actions">
                        <a href="/products" class="btn-primary cta-btn-main reveal-right reveal-delay-5">
                            Belanja Sekarang
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </a>
                        <a href="/products/chrisbale" class="cta-btn-ghost reveal-right reveal-delay-6">
                            Lihat Koleksi CHRISBALE
                        </a>
                    </div>

                    <!-- Urgency note -->
                    <p class="cta-urgency reveal-delay-6">
                        <span class="cta-urgency-dot"></span>
                        <strong>{{ rand(2, 90) }} orang</strong> sedang melihat koleksi ini sekarang
                    </p>
                </div>

                <!-- Right: Product Showcase Stack -->
                <div class="cta-buy-visual reveal-right">
                    <div class="cta-prod-stack">
                        <div class="cta-prod-card cta-prod-card--main reveal-scale reveal-delay-1">
                            <img src="https://p16-oec-sg.ibyteimg.com/tos-alisg-i-aphluv4xwc-sg/029280cbc678421b83dce1f2c766ce31~tplv-aphluv4xwc-white-pad-v1:500:500.jpeg?w=600&q=80&auto=format&fit=crop" alt="CHRISBALE Urban Runner" loading="lazy">
                            <div class="cta-prod-info">
                                <span class="cta-prod-name">Sandal Casual</span>
                                <span class="cta-prod-badge">Terlaris</span>
                            </div>
                        </div>
                        <div class="cta-prod-row">
                            <div class="cta-prod-card cta-prod-card--sm reveal-scale reveal-delay-2">
                                <img src="https://down-id.img.susercontent.com/file/id-11134207-82250-mkm4cvv5jqx043@resize_w900_nl.webp?w=400&q=80&auto=format&fit=crop" alt="Gold Leather Loafer" loading="lazy">
                                <span class="cta-prod-name-sm">Sandal Wedges</span>
                            </div>
                            <div class="cta-prod-card cta-prod-card--sm reveal-scale reveal-delay-3">
                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSQwQ2JrQTV8ems5XIU3TuPjNJwt2gAGsoYZrMeIyinQykoPwubq44j5dbM&s=10?w=400&q=80&auto=format&fit=crop" alt="Combat Boot" loading="lazy">
                                <span class="cta-prod-name-sm">Sendal Wanita</span>
                            </div>
                        </div>
                    </div>
                    <!-- Floating review badge -->
                    <div class="cta-float-badge reveal-scale reveal-delay-4">
                        <div class="cfb-stars">
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        </div>
                        <span class="cfb-text"><strong>4.9</strong> / 2.418 ulasan</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        /* HERO CAROUSEL */
        (function() {
            const INTERVAL = 5000;
            const TRANSITION = 800;
            const track = document.getElementById('heroTrack');
            if (!track) return;
            const slides = Array.from(track.querySelectorAll('.hero-slide'));
            const dots = Array.from(document.querySelectorAll('.carousel-dot'));
            const counter = document.getElementById('heroCounter');
            const progress = document.getElementById('heroProgress');
            const prevBtn = document.getElementById('heroPrev');
            const nextBtn = document.getElementById('heroNext');
            const heroEl = document.getElementById('heroCarousel');
            const total = slides.length;
            let current = 0;
            let timer = null;
            let isAnimating = false;

            function pad(n) {
                return String(n + 1).padStart(2, '0');
            }

            function goTo(idx, fromUser) {
                if (isAnimating) return;
                isAnimating = true;
                slides[current].classList.remove('is-active');
                if (dots[current]) dots[current].classList.remove('active');
                current = (idx + total) % total;
                track.style.transform = 'translateX(-' + (current * 100) + '%)';
                setTimeout(function() {
                    slides[current].classList.add('is-active');
                    isAnimating = false;
                }, TRANSITION * 0.4);
                if (dots[current]) dots[current].classList.add('active');
                if (counter) counter.textContent = pad(current) + ' / ' + pad(total - 1);
                resetProgress();
                if (!fromUser) {
                    clearInterval(timer);
                    timer = setInterval(function() {
                        goTo(current + 1);
                    }, INTERVAL);
                }
            }

            function resetProgress() {
                if (!progress) return;
                progress.style.transition = 'none';
                progress.style.width = '0%';
                void progress.offsetWidth;
                progress.style.transition = 'width ' + INTERVAL + 'ms linear';
                progress.style.width = '100%';
            }

            function startAuto() {
                clearInterval(timer);
                timer = setInterval(function() {
                    goTo(current + 1);
                }, INTERVAL);
                resetProgress();
            }

            if (prevBtn) {
                prevBtn.addEventListener('click', function() {
                    goTo(current - 1, true);
                    startAuto();
                });
            }
            if (nextBtn) {
                nextBtn.addEventListener('click', function() {
                    goTo(current + 1, true);
                    startAuto();
                });
            }
            dots.forEach(function(dot, i) {
                dot.addEventListener('click', function() {
                    if (i !== current) {
                        goTo(i, true);
                        startAuto();
                    }
                });
            });

            if (heroEl) {
                var touchStartX = 0;
                heroEl.addEventListener('touchstart', function(e) {
                    touchStartX = e.changedTouches[0].clientX;
                }, { passive: true });
                heroEl.addEventListener('touchend', function(e) {
                    var diff = touchStartX - e.changedTouches[0].clientX;
                    if (Math.abs(diff) > 50) {
                        diff > 0 ? goTo(current + 1, true) : goTo(current - 1, true);
                        startAuto();
                    }
                }, { passive: true });
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowLeft') {
                    goTo(current - 1, true);
                    startAuto();
                }
                if (e.key === 'ArrowRight') {
                    goTo(current + 1, true);
                    startAuto();
                }
            });

            if (slides[0]) slides[0].classList.add('is-active');
            if (counter) counter.textContent = pad(0) + ' / ' + pad(total - 1);
            startAuto();
        })();

        /* TESTIMONIAL CAROUSEL */
        (function() {
            const track = document.getElementById('testiTrack');
            if (!track) return;

            const cards = Array.from(track.querySelectorAll('.testi-card-v2'));
            const dots  = Array.from(document.querySelectorAll('.testi-dot'));
            const prevBtn = document.getElementById('testiPrev');
            const nextBtn = document.getElementById('testiNext');
            const total = cards.length;
            if (total === 0) return;

            let current = 0;
            let isDragging = false;
            let startX = 0;
            let currentTranslate = 0;
            let prevTranslate = 0;
            let dragDistance = 0;

            function getVisible() {
                const w = window.innerWidth;
                if (w <= 600) return 1;
                if (w <= 992) return 2;
                return 3;
            }

            function getMaxIndex() {
                return Math.max(0, total - getVisible());
            }

            function getCardStep() {
                if (cards.length > 1) {
                    return cards[1].offsetLeft - cards[0].offsetLeft;
                }
                if (cards[0]) {
                    const style = window.getComputedStyle(track);
                    const gap = parseFloat(style.gap) || 20;
                    return cards[0].offsetWidth + gap;
                }
                return 0;
            }

            function updateUI() {
                const max = getMaxIndex();
                dots.forEach(function(d, i) {
                    d.classList.toggle('active', i === current);
                    d.style.display = i <= max ? '' : 'none';
                });
                if (prevBtn) {
                    prevBtn.disabled = current <= 0;
                    prevBtn.style.opacity = current <= 0 ? '0.35' : '1';
                }
                if (nextBtn) {
                    nextBtn.disabled = current >= max;
                    nextBtn.style.opacity = current >= max ? '0.35' : '1';
                }
            }

            function setPositionByIndex(idx, animate) {
                const max = getMaxIndex();
                current = Math.min(Math.max(idx, 0), max);
                const step = getCardStep();
                currentTranslate = -current * step;
                prevTranslate = currentTranslate;

                if (animate !== false) {
                    track.classList.remove('is-dragging');
                } else {
                    track.classList.add('is-dragging');
                }

                track.style.transform = 'translateX(' + currentTranslate + 'px)';
                updateUI();
            }

            function goTo(idx) {
                setPositionByIndex(idx, true);
            }

            if (prevBtn) {
                prevBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    goTo(current - 1);
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    goTo(current + 1);
                });
            }

            dots.forEach(function(dot, i) {
                dot.addEventListener('click', function(e) {
                    e.preventDefault();
                    goTo(i);
                });
            });

            // Pointer events for Drag & Swipe (Touch + Mouse)
            function onPointerDown(e) {
                isDragging = true;
                dragDistance = 0;
                startX = e.clientX || (e.touches && e.touches[0] ? e.touches[0].clientX : 0);
                track.classList.add('is-dragging');
                try {
                    track.setPointerCapture(e.pointerId);
                } catch (err) {}
            }

            function onPointerMove(e) {
                if (!isDragging) return;
                const currentX = e.clientX || (e.touches && e.touches[0] ? e.touches[0].clientX : 0);
                const diff = currentX - startX;
                dragDistance = diff;

                const step = getCardStep();
                const maxTranslate = -getMaxIndex() * step;
                let move = prevTranslate + diff;

                // Resistance at edges
                if (move > 0) {
                    move = diff * 0.25;
                } else if (move < maxTranslate) {
                    move = maxTranslate + (diff * 0.25);
                }

                track.style.transform = 'translateX(' + move + 'px)';
            }

            function onPointerUp(e) {
                if (!isDragging) return;
                isDragging = false;
                track.classList.remove('is-dragging');
                try {
                    track.releasePointerCapture(e.pointerId);
                } catch (err) {}

                const threshold = Math.min(60, getCardStep() * 0.2);
                if (dragDistance < -threshold) {
                    goTo(current + 1);
                } else if (dragDistance > threshold) {
                    goTo(current - 1);
                } else {
                    goTo(current);
                }
            }

            if (window.PointerEvent) {
                track.addEventListener('pointerdown', onPointerDown);
                track.addEventListener('pointermove', onPointerMove);
                track.addEventListener('pointerup', onPointerUp);
                track.addEventListener('pointercancel', onPointerUp);
            } else {
                // Fallback touch & mouse
                track.addEventListener('touchstart', onPointerDown, { passive: true });
                track.addEventListener('touchmove', onPointerMove, { passive: true });
                track.addEventListener('touchend', onPointerUp);
                track.addEventListener('mousedown', onPointerDown);
                window.addEventListener('mousemove', onPointerMove);
                window.addEventListener('mouseup', onPointerUp);
            }

            // Prevent drag links/images dragging
            track.querySelectorAll('img, a').forEach(function(el) {
                el.addEventListener('dragstart', function(e) { e.preventDefault(); });
            });

            // Prevent click if user was dragging
            track.addEventListener('click', function(e) {
                if (Math.abs(dragDistance) > 8) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            }, true);

            window.addEventListener('resize', function() {
                setPositionByIndex(current, false);
            });

            // Initial alignment
            setTimeout(function() {
                goTo(0);
            }, 50);
        })();

        /* SCROLL REVEAL ANIMATIONS */
        (function() {
            const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (prefersReduced) return;

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        // Trigger bar fill animation
                        if (entry.target.classList.contains('trs-bar-row')) {
                            const fill = entry.target.querySelector('.trs-bar-fill');
                            const target = entry.target.style.getPropertyValue('--target-width').trim();
                            if (fill && target) {
                                fill.style.width = target;
                            }
                        }
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                root: null,
                rootMargin: '0px 0px -50px 0px',
                threshold: 0.1
            });

            document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale, .trs-bar-row').forEach(function(el) {
                observer.observe(el);
            });
        })();
    </script>
@endpush
