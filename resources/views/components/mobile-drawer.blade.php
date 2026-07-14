<div class="drawer-overlay" id="drawerOverlay"></div>
<div class="mobile-nav-drawer" id="mobileDrawer">
    <div class="drawer-header">
        <a href="/" class="logo" style="font-size:20px;">CHRIS<span>BALE</span></a>
        <button class="drawer-close" id="drawerClose" aria-label="Tutup menu">&#215;</button>
    </div>
    <div class="drawer-search">
        <div class="drawer-search-inner">
            <input type="search" placeholder="Cari produk...">
            <button aria-label="Cari">
                <svg viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="7" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
            </button>
        </div>
    </div>
    <nav class="mobile-nav-links">
        <a href="/" {{ $active === '/' ? 'class="active"' : '' }}>Beranda</a>
        <a href="/about" {{ $active === 'about' ? 'class="active"' : '' }}>Tentang</a>
        <a href="/products"
            {{ $active === 'products' || str_contains($active, 'products') ? 'class="active"' : '' }}>Semua Produk</a>
        <a href="/products/chrisbale">CHRISBALE</a>
        <a href="/products/agatha">Agatha</a>
        <a href="/contact" {{ $active === 'contact' ? 'class="active"' : '' }}>Kontak</a>
    </nav>
    <div class="drawer-auth">
        <a href="/login" class="drawer-auth-btn auth-guest">Masuk</a>
        <a href="/register" class="drawer-auth-btn drawer-auth-btn--primary auth-guest">Daftar</a>
        <a href="/logout" class="drawer-auth-btn auth-user" id="logoutBtn">Keluar</a>
    </div>
</div>
