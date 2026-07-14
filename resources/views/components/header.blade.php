@props(['active' => '/'])
<header class="header-bar" id="top">
  <div class="header-row-1">
    <button class="hamburger" id="menuToggle" aria-label="Buka menu">
      <span></span><span></span><span></span>
    </button>
    <a href="/" class="logo">CHRIS<span>BALE</span></a>
    <form class="search-bar" role="search" onsubmit="return false;">
      <input type="search" placeholder="Cari produk..." aria-label="Cari produk">
      <button type="submit" aria-label="Cari">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      </button>
    </form>
    <div class="header-actions">
      <a href="/login" class="header-action-item auth-guest" aria-label="Masuk">
        <svg viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M13 12H3"/></svg>
        <span class="label">Masuk</span>
      </a>
      <a href="/register" class="header-action-item auth-guest" aria-label="Daftar">
        <svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
        <span class="label">Daftar</span>
      </a>
      <a href="#cart" class="header-action-item auth-user" aria-label="Keranjang" style="position:relative;">
        <svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
        <span class="label">Keranjang</span>
        <span class="action-badge">3</span>
      </a>
      <a href="#account" class="header-action-item auth-user" aria-label="Akun">
        <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        <span class="label">Akun</span>
      </a>
      <a href="/logout" class="header-action-item auth-user" aria-label="Keluar">
        <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
        <span class="label">Keluar</span>
      </a>
    </div>
  </div>
  <div class="header-row-2">
    <nav class="header-nav">
      <a href="/" {{ $active === '/' ? 'class="active"' : '' }}>Beranda</a>
      <a href="/about" {{ $active === 'about' ? 'class="active"' : '' }}>Tentang</a>
      <div class="nav-dropdown">
        <a href="/products" class="nav-link{{ $active === 'products' || str_contains($active, 'products') ? ' active' : '' }}">
          Produk
          <svg class="arrow" viewBox="0 0 12 12"><path d="M2 4l4 4 4-4"/></svg>
        </a>
        <div class="drop-menu">
          <a href="/products">Semua Produk</a>
          <div class="drop-divider"></div>
          <a href="/products/chrisbale">&#9679; CHRISBALE</a>
          <a href="/products/agatha">&#9679; Agatha</a>
        </div>
      </div>
      <a href="/contact" {{ $active === 'contact' ? 'class="active"' : '' }}>Kontak</a>
    </nav>
  </div>
</header>
