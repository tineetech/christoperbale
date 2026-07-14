@extends('layouts.app')

@section('title')
@if($brand) {{ $brand }} — @endif Produk — CHRISBALE
@endsection

@push('styles')
<style>
.brand-tabs{display:flex;justify-content:center;gap:8px;margin-top:24px;}
.brand-tab{padding:8px 24px;border-radius:999px;font-size:12.5px;font-weight:500;border:1px solid rgba(255,255,255,0.2);color:rgba(255,255,255,0.6);transition:all .2s;}
.brand-tab:hover{border-color:rgba(255,255,255,0.5);color:#fff;}
.brand-tab.active{background:var(--accent);border-color:var(--accent);color:#fff;}
.filter-bar{display:flex;align-items:center;gap:16px;flex-wrap:wrap;margin-bottom:24px;}
.filter-bar .search-wrap{flex:1;min-width:200px;position:relative;}
.filter-bar .search-wrap input{width:100%;padding:12px 16px 12px 42px;border:1.5px solid var(--line);border-radius:var(--radius-sm);font-size:13.5px;font-family:'Inter',sans-serif;color:var(--ink);background:var(--bg-card);outline:none;transition:border-color .2s;box-sizing:border-box;}
.filter-bar .search-wrap input:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(184,134,11,0.1);}
.filter-bar .search-wrap input::placeholder{color:var(--ink-muted);}
.filter-bar .search-wrap .search-icon{position:absolute;left:14px;top:50%;transform:translateY(-50%);width:16px;height:16px;stroke:var(--ink-muted);fill:none;stroke-width:2;pointer-events:none;}
.filter-bar .filter-select{padding:12px 38px 12px 16px;border:1.5px solid var(--line);border-radius:var(--radius-sm);font-size:13px;font-family:'Inter',sans-serif;color:var(--ink-soft);background:var(--bg-card);outline:none;cursor:pointer;transition:border-color .2s;appearance:none;-webkit-appearance:none;-moz-appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath d='M2 4l4 4 4-4' stroke='%238A8580' stroke-width='1.5' fill='none'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 14px center;flex-shrink:0;}
.filter-bar .filter-select:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(184,134,11,0.1);}
.grid-prod{background:transparent;border:none;}
.parallax-head{position:relative;overflow:hidden;padding:100px 0;}
.parallax-bg{position:absolute;inset:0;}
.parallax-bg img{width:100%;height:100%;object-fit:cover;}
.parallax-overlay{position:absolute;inset:0;background:linear-gradient(135deg,rgba(10,8,6,0.85) 0%,rgba(10,8,6,0.65) 50%,rgba(10,8,6,0.8) 100%);}
@media(max-width:768px){.parallax-head{padding:70px 0;}}
</style>
@endpush

@section('content')

<section class="page-head parallax-head">
  <div class="parallax-bg"><img src="https://images.unsplash.com/photo-1549298916-b41d501d3772?w=1400&q=80&auto=format&fit=crop" alt="" id="parallaxImg"></div>
  <div class="parallax-overlay"></div>
  <div class="wrap" style="position:relative;z-index:2;">
    <h1>
      @if($brand)
        Koleksi {{ $brand }}
      @else
        Semua Produk
      @endif
    </h1>
    <p>
      @if($brand)
        Jelajahi koleksi lengkap {{ $brand }} — alas kaki premium untuk setiap langkah.
      @else
        Lihat katalog lengkap kami yang menampilkan koleksi CHRISBALE dan Agatha.
      @endif
    </p>
    <div class="brand-tabs">
      <a href="/products" class="brand-tab {{ !$brand ? 'active' : '' }}">Semua</a>
      <a href="/products/chrisbale" class="brand-tab {{ $brand === 'CHRISBALE' ? 'active' : '' }}">CHRISBALE</a>
      <a href="/products/agatha" class="brand-tab {{ $brand === 'Agatha' ? 'active' : '' }}">Agatha</a>
    </div>
  </div>
</section>

@php
if ($brand) {
  $filtered = array_filter($allProducts, fn($p) => $p['brand'] === $brand);
} else {
  $filtered = $allProducts;
}
@endphp

<section class="section">
  <div class="wrap">
    <div class="section-head">
      <div>
        <h2>Koleksi Alas Kaki Premium</h2>
        <p>Temukan sneaker urban, boots klasik, loafers elegan, dan sandal kasual — masing-masing dirancang untuk kenyamanan dan gaya sepanjang hari.</p>
      </div>
    </div>

    <div class="filter-bar" id="filterBar">
      <div class="search-wrap">
        <svg class="search-icon" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" id="searchInput" placeholder="Cari produk..." oninput="applyFilters()">
      </div>
      <select class="filter-select" id="brandFilter" onchange="applyFilters()">
        <option value="">Semua Brand</option>
        <option value="CHRISBALE">CHRISBALE</option>
        <option value="Agatha">Agatha</option>
      </select>
      <select class="filter-select" id="categoryFilter" onchange="applyFilters()">
        <option value="">Semua Kategori</option>
        <option value="Sneakers">Sneakers</option>
        <option value="Boots">Boots</option>
        <option value="Sandal">Sandal</option>
        <option value="Loafers">Loafers</option>
      </select>
      <select class="filter-select" id="sortFilter" onchange="applyFilters()">
        <option value="">Urutkan</option>
        <option value="price-asc">Harga: Rendah ke Tinggi</option>
        <option value="price-desc">Harga: Tinggi ke Rendah</option>
        <option value="name-asc">Nama: A-Z</option>
        <option value="name-desc">Nama: Z-A</option>
      </select>
      <select class="filter-select" id="perPageFilter" onchange="applyFilters()">
        <option value="10">10 / Halaman</option>
        <option value="20">20 / Halaman</option>
        <option value="40">40 / Halaman</option>
        <option value="100">Semua</option>
      </select>
    </div>

    <div class="grid-prod" id="productGrid">
      @foreach($filtered as $product)
      <article class="prod-card" data-name="{{ strtolower($product['name']) }}" data-brand="{{ $product['brand'] }}" data-category="{{ $product['category'] ?? '' }}" data-price="{{ $product['price'] }}" onclick="window.location='/product/{{ $product['slug'] }}'">
        @if($product['badge'])
          <span class="badge-tag badge-{{ $product['badge'] }}">
            @switch($product['badge'])
              @case('new') Baru @break
              @case('sale') -{{ round((1 - $product['price']/$product['old']) * 100) }}%@break
              @case('hot') Populer @break
            @endswitch
          </span>
        @endif
        <div class="prod-img-wrap">
          <img src="{{ $product['img'] }}" alt="{{ $product['name'] }}" loading="lazy">
          <span class="badge-brand">{{ $product['brand'] }}</span>
          <div class="prod-overlay"><button class="add-btn" onclick="event.stopPropagation()">+ Keranjang</button></div>
        </div>
        <div class="prod-info">
          <h3>{{ $product['name'] }}</h3>
          <div class="price-row">
            <span class="current">Rp{{ number_format($product['price'] * 15000, 0, ',', '.') }}</span>
            @if($product['old'])
              <span class="old">Rp{{ number_format($product['old'] * 15000, 0, ',', '.') }}</span>
            @endif
          </div>
        </div>
      </article>
      @endforeach
    </div>

    <div id="emptyState" style="display:none;text-align:center;padding:80px 20px;">
      <svg style="width:48px;height:48px;stroke:var(--ink-muted);fill:none;stroke-width:1.2;margin:0 auto 16px;" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
      <p style="color:var(--ink-muted);font-size:15px;margin:0;">Tidak ada produk yang cocok dengan pencarian Anda.</p>
    </div>

    <div class="pagination" id="pagination"></div>
  </div>
</section>

<style>
.pagination{display:flex;justify-content:center;align-items:center;gap:6px;margin-top:40px;flex-wrap:wrap;}
.pagination button{min-width:40px;height:40px;padding:0 12px;border:1.5px solid var(--line);border-radius:var(--radius-sm);font-size:13px;font-weight:500;color:var(--ink-soft);background:var(--bg-card);cursor:pointer;transition:all .2s;font-family:'Inter',sans-serif;}
.pagination button:hover:not(:disabled):not(.active){border-color:var(--accent);color:var(--accent);}
.pagination button.active{border-color:var(--accent);background:var(--accent);color:#fff;}
.pagination button:disabled{opacity:0.35;cursor:default;}
.pagination .page-info{font-size:13px;color:var(--ink-muted);padding:0 8px;white-space:nowrap;}
@media(max-width:600px){.pagination button{min-width:36px;height:36px;font-size:12px;padding:0 8px;}}
</style>

@endsection

@push('scripts')
<script>
var currentPage = 1;

function getPerPage(){
  return parseInt(document.getElementById('perPageFilter').value);
}

function applyFilters(){
  currentPage = 1;
  renderProducts();
}

function renderProducts(){
  var query = document.getElementById('searchInput').value.toLowerCase().trim();
  var brand = document.getElementById('brandFilter').value;
  var category = document.getElementById('categoryFilter').value;
  var sort = document.getElementById('sortFilter').value;
  var grid = document.getElementById('productGrid');
  var cards = Array.from(grid.querySelectorAll('.prod-card'));
  var visible = [];

  cards.forEach(function(card){
    var name = card.getAttribute('data-name');
    var cat = card.getAttribute('data-category');
    var br = card.getAttribute('data-brand');
    var match = true;
    if(query && !name.includes(query)) match = false;
    if(brand && br !== brand) match = false;
    if(category && cat !== category) match = false;
    card.dataset._visible = match ? '1' : '0';
    if(match) visible.push(card);
  });

  if(sort){
    visible.sort(function(a,b){
      var key = sort.split('-')[0];
      var av = a.getAttribute('data-'+key);
      var bv = b.getAttribute('data-'+key);
      if(key==='price'){av=parseFloat(av);bv=parseFloat(bv);}
      if(sort.endsWith('asc')) return av > bv ? 1 : -1;
      return av < bv ? 1 : -1;
    });
  }

  var perPage = getPerPage();
  var totalPages = Math.max(1, Math.ceil(visible.length / perPage));
  if(currentPage > totalPages) currentPage = totalPages;

  var start = (currentPage - 1) * perPage;
  var end = start + perPage;
  var pageItems = visible.slice(start, end);

  cards.forEach(function(card){ card.style.display = 'none'; });
  pageItems.forEach(function(card){ card.style.display = ''; });

  document.getElementById('emptyState').style.display = visible.length === 0 ? '' : 'none';
  renderPagination(totalPages);
}

function renderPagination(totalPages){
  var el = document.getElementById('pagination');
  if(totalPages <= 1){ el.innerHTML = ''; return; }

  var html = '';
  html += '<button ' + (currentPage === 1 ? 'disabled' : '') + ' onclick="goPage('+(currentPage-1)+')">‹</button>';

  var startPage = Math.max(1, currentPage - 2);
  var endPage = Math.min(totalPages, currentPage + 2);
  if(endPage - startPage < 4){ startPage = Math.max(1, endPage - 4); endPage = Math.min(totalPages, startPage + 4); }

  if(startPage > 1){ html += '<button onclick="goPage(1)">1</button>'; if(startPage > 2) html += '<span class="page-info">…</span>'; }

  for(var i = startPage; i <= endPage; i++){
    html += '<button class="' + (i === currentPage ? 'active' : '') + '" onclick="goPage('+i+')">'+i+'</button>';
  }

  if(endPage < totalPages){ if(endPage < totalPages - 1) html += '<span class="page-info">…</span>'; html += '<button onclick="goPage('+totalPages+')">'+totalPages+'</button>'; }

  html += '<button ' + (currentPage === totalPages ? 'disabled' : '') + ' onclick="goPage('+(currentPage+1)+')">›</button>';
  el.innerHTML = html;
}

function goPage(n){
  currentPage = n;
  renderProducts();
}

renderProducts();
</script>
@endpush
