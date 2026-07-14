@extends('layouts.app')

@section('title', $product['name'] . ' — CHRISBALE')

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
          @if($product['badge'])
            <span class="badge-tag badge-{{ $product['badge'] }}">
              @switch($product['badge'])
                @case('new') Baru @break
                @case('sale')
                  @if($product['old'] && $product['old'] > $product['price'])
                    -{{ round((1 - $product['price']/$product['old']) * 100) }}%
                  @else
                    Sale
                  @endif
                @break
                @case('hot') Populer @break
              @endswitch
            </span>
          @endif
          <img src="{{ $product['img'] }}" alt="{{ $product['name'] }}" id="pdMainImage">
        </div>
        <div class="pd-thumbs">
          <div class="thumb active"><img src="{{ $product['img'] }}" alt=""></div>
          <div class="thumb"><img src="{{ $product['img'] }}" alt=""></div>
          <div class="thumb"><img src="{{ $product['img'] }}" alt=""></div>
        </div>
      </div>

      <div class="pd-info-col">
        <div class="pd-brand">{{ $product['brand'] }}</div>
        <h1 class="pd-name">{{ $product['name'] }}</h1>

        <div class="pd-rating">
          <div class="stars">
            <svg class="star-full" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            <svg class="star-full" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            <svg class="star-full" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            <svg class="star-full" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            <svg class="star-half" viewBox="0 0 24 24" style="position:relative;">
              <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" fill="none" stroke="var(--ink-muted)" stroke-width="1.5"/>
              <clipPath id="halfClip"><rect x="0" y="0" width="12" height="24"/></clipPath>
              <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" fill="var(--accent)" clip-path="url(#halfClip)"/>
            </svg>
          </div>
          <span class="count">({{ $product['review_count'] ?? 42 }} ulasan)</span>
        </div>

        <div class="pd-price-row">
          <span class="current">Rp{{ number_format($product['price'] * 15000, 0, ',', '.') }}</span>
          @if($product['old'] && $product['old'] > $product['price'])
            <span class="old">Rp{{ number_format($product['old'] * 15000, 0, ',', '.') }}</span>
            <span class="discount">-{{ round((1 - $product['price']/$product['old']) * 100) }}%</span>
          @endif
        </div>

        <div class="pd-divider"></div>

        @if(!empty($product['sizes']))
        <div class="pd-label">Pilih Ukuran</div>
        <div class="pd-sizes">
          @foreach($product['sizes'] as $size)
          <div class="pd-size-option">
            <input type="radio" name="size" id="size-{{ $loop->index }}" value="{{ $size }}" {{ $loop->first ? 'checked' : '' }}>
            <label for="size-{{ $loop->index }}">{{ $size }}</label>
          </div>
          @endforeach
        </div>
        @endif

        @if(!empty($product['colors']))
        <div class="pd-label">Pilih Warna</div>
        <div class="pd-colors">
          @foreach($product['colors'] as $color)
          <div class="pd-color-option">
            <input type="radio" name="color" id="color-{{ $loop->index }}" value="{{ $color['name'] ?? $color }}" {{ $loop->first ? 'checked' : '' }}>
            <label for="color-{{ $loop->index }}" style="background:{{ $color['hex'] ?? '#333' }};" title="{{ $color['name'] ?? $color }}"></label>
          </div>
          @endforeach
        </div>
        @endif

        <div class="pd-label">Jumlah</div>
        <div class="pd-qty">
          <button class="qty-minus" onclick="document.getElementById('qtyInput').stepDown();updateQty();">&#8722;</button>
          <input type="number" id="qtyInput" value="1" min="1" max="99" onchange="updateQty();">
          <button class="qty-plus" onclick="document.getElementById('qtyInput').stepUp();updateQty();">&#43;</button>
        </div>

        <button class="pd-add-cart">Tambah ke Keranjang</button>
        <button class="pd-wishlist">
          <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
          Simpan ke Wishlist
        </button>

        @if(!empty($product['desc']))
        <div class="pd-divider"></div>
        <div class="pd-desc">
          <h2>Deskripsi Produk</h2>
          <p>{{ $product['desc'] }}</p>
        </div>
        @endif

        @if(!empty($product['features']))
        <div class="pd-features">
          <h2>Fitur Utama</h2>
          <ul>
            @foreach($product['features'] as $feature)
            <li>
              <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
              {{ $feature }}
            </li>
            @endforeach
          </ul>
        </div>
        @endif

        <div class="pd-meta">
          <dt>SKU</dt>
          <dd>{{ $product['sku'] ?? strtoupper(substr(md5($product['name']), 0, 8)) }}</dd>
          <dt>Kategori</dt>
          <dd>{{ $product['brand'] }} — {{ $product['category'] ?? 'Alas Kaki' }}</dd>
        </div>
      </div>
    </div>
  </div>
</section>

@if(!empty($allProducts))
@php
  $related = collect($allProducts)->where('slug', '!=', $product['slug'])->take(4);
@endphp
@if($related->count() > 0)
<section class="related-section">
  <div class="wrap">
    <h2>Produk Terkait</h2>
    <div class="grid-related">
      @foreach($related as $rp)
      <a href="/product/{{ $rp['slug'] }}" class="prod-card">
        @if($rp['badge'])
          <span class="badge-tag badge-{{ $rp['badge'] }}">
            @switch($rp['badge'])
              @case('new') Baru @break
              @case('sale')
                @if($rp['old'] && $rp['old'] > $rp['price'])
                  -{{ round((1 - $rp['price']/$rp['old']) * 100) }}%
                @else
                  Sale
                @endif
              @break
              @case('hot') Populer @break
            @endswitch
          </span>
        @endif
        <div class="prod-img-wrap">
          <img src="{{ $rp['img'] }}" alt="{{ $rp['name'] }}" loading="lazy">
          <div class="prod-overlay"><span class="add-btn">Lihat Detail</span></div>
        </div>
        <div class="prod-info">
          <div class="prod-brand">{{ $rp['brand'] }}</div>
          <h3>{{ $rp['name'] }}</h3>
          <div class="price-row">
            <span class="current">Rp{{ number_format($rp['price'] * 15000, 0, ',', '.') }}</span>
            @if($rp['old'])
              <span class="old">Rp{{ number_format($rp['old'] * 15000, 0, ',', '.') }}</span>
            @endif
          </div>
        </div>
      </a>
      @endforeach
    </div>
  </div>
</section>
@endif
@endif

@endsection

@push('scripts')
<script>
function updateQty(){var i=document.getElementById('qtyInput');if(i.value<1)i.value=1;if(i.value>99)i.value=99;}
document.querySelectorAll('.pd-thumbs .thumb').forEach(function(t){t.addEventListener('click',function(){document.querySelectorAll('.pd-thumbs .thumb').forEach(function(x){x.classList.remove('active')});this.classList.add('active');document.getElementById('pdMainImage').src=this.querySelector('img').src;});});
document.querySelectorAll('.pd-size-option input').forEach(function(r){r.addEventListener('change',function(){document.querySelectorAll('.pd-size-option input').forEach(function(x){if(x.checked)x.closest('.pd-size-option').querySelector('label').style.borderColor='';});if(this.checked)this.closest('.pd-size-option').querySelector('label').style.borderColor='var(--accent)';});});
</script>
@endpush
