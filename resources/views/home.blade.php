@extends('layouts.app')

@section('title', 'CHRISBALE — Premium Footwear Marketplace')

@section('content')

<!-- HERO CAROUSEL -->
<section class="hero" id="heroCarousel">
  <div class="hero-track" id="heroTrack">
    <div class="hero-slide is-active">
      <div class="hero-bg">
        <div class="hero-bg-left">
          <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=900&q=80&auto=format&fit=crop" alt="CHRISBALE Sneaker">
        </div>
        <div class="hero-bg-right">
          <img src="https://images.unsplash.com/photo-1549298916-b41d501d3772?w=900&q=80&auto=format&fit=crop" alt="CHRISBALE Footwear">
        </div>
      </div>
      <div class="hero-overlay">
        <span class="hero-tag">&#9679; Koleksi Baru 2026</span>
        <h1>Melangkah dengan <em>Gaya</em> —<br>Kenyamanan Bertemu<br>Elegansi Urban</h1>
        <p>Temukan koleksi alas kaki terbaru yang dikurasi untuk langkah modern. Dari sneaker premium hingga boots buatan tangan — setiap langkah bercerita.</p>
        <div class="hero-actions">
          <a href="#shop" class="btn-primary">Beli Sekarang <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
          <a href="#sale" class="btn-outline-hero">Jelajahi</a>
        </div>
      </div>
    </div>
    <div class="hero-slide">
      <div class="hero-bg-full">
        <img src="https://images.unsplash.com/photo-1539185441755-769473a23570?w=1400&q=80&auto=format&fit=crop" alt="Limited Edition">
      </div>
      <div class="hero-overlay">
        <span class="hero-tag">&#9733; Edisi Terbatas</span>
        <h1>Hanya <em>500 Pasang</em><br>Sneaker Kulit Italia<br>Buatan Tangan</h1>
        <p>Kulit Italia full-grain, insole memory foam, dan emblem emas khas kami. Eksklusif hanya di CHRISBALE — sebelum habis.</p>
        <div class="hero-actions">
          <a href="#sale" class="btn-primary">Beli Koleksi <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
          <a href="#new" class="btn-outline-hero">Lihat Semua</a>
        </div>
      </div>
    </div>
    <div class="hero-slide">
      <div class="hero-bg">
        <div class="hero-bg-left">
          <img src="https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?w=900&q=80&auto=format&fit=crop" alt="Sale Boots">
        </div>
        <div class="hero-bg-right">
          <img src="https://images.unsplash.com/photo-1543163521-1bf539c55dd2?w=900&q=80&auto=format&fit=crop" alt="Sale Chelsea">
        </div>
      </div>
      <div class="hero-overlay">
        <span class="hero-tag" style="border-color:rgba(192,57,43,0.5);color:#FF8A75;">&#9670; Flash Sale — Berakhir Minggu Ini</span>
        <h1>Diskon Hingga <em style="color:#FF8A75;">30%</em><br>Boots Premium &amp;<br>Seri Signature</h1>
        <p>Sale terbesar musim ini telah tiba. Dapatkan boots buatan tangan, loafers kulit, dan high-top ikonik dengan harga tak tertandingi. Stok terbatas.</p>
        <div class="hero-actions">
          <a href="#sale" class="btn-primary" style="background:#C0392B;">Beli di Sale <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
          <a href="#shop" class="btn-outline-hero">Jelajahi Semua</a>
        </div>
      </div>
    </div>
    <div class="hero-slide">
      <div class="hero-bg-full">
        <img src="https://images.unsplash.com/photo-1491553895911-0055eca6402d?w=1400&q=80&auto=format&fit=crop" alt="New Arrivals">
      </div>
      <div class="hero-overlay">
        <span class="hero-tag">&#9632; Baru Minggu Ini</span>
        <h1>Fresh Drops —<br>Dibuat untuk <em>Jalanan</em><br>dan Studio</h1>
        <p>Performa siap-pakai bertemu desain mewah minimalis. Siluet baru baru saja tiba — dikurasi untuk mereka yang bergerak dengan penuh tujuan.</p>
        <div class="hero-actions">
          <a href="#new" class="btn-primary">Lihat Barang Baru <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
          <a href="#shop" class="btn-outline-hero">Jelajahi Semua</a>
        </div>
      </div>
    </div>
  </div>

  <button class="carousel-arrow prev" id="heroPrev" aria-label="Slide sebelumnya">
    <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
  </button>
  <button class="carousel-arrow next" id="heroNext" aria-label="Slide berikutnya">
    <svg viewBox="0 0 24 24"><polyline points="9 6 15 12 9 18"/></svg>
  </button>

  <div class="carousel-dots" id="heroDots">
    <button class="carousel-dot active" data-index="0" aria-label="Slide 1"></button>
    <button class="carousel-dot" data-index="1" aria-label="Slide 2"></button>
    <button class="carousel-dot" data-index="2" aria-label="Slide 3"></button>
    <button class="carousel-dot" data-index="3" aria-label="Slide 4"></button>
  </div>

  <div class="carousel-counter" id="heroCounter">01 / 04</div>
  <div class="carousel-progress" id="heroProgress"></div>
</section>

<!-- MARQUEE -->
<div class="marquee">
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
</div>

<!-- PRODUCT GRID — Best Sellers -->
<section class="section" id="shop">
  <div class="wrap">
    <div class="section-head">
      <div class="section-head-left">
        <span class="eyebrow">Terlaris</span>
        <h2>Paling Populer Minggu Ini</h2>
        <p>Pilihan terbaik dari koleksi terbaru kami — disukai ribuan orang.</p>
      </div>
      <a href="#" class="section-link">Lihat Semua</a>
    </div>
    <div class="grid-prod">
      <article class="prod-card" onclick="window.location='/product/chrisbale-urban-runner'">
        <span class="badge-tag badge-new">Baru</span>
        <div class="prod-img-wrap">
          <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&q=80&auto=format&fit=crop" alt="CHRISBALE Urban Runner" loading="lazy">
          <div class="prod-overlay"><button class="add-btn" onclick="event.stopPropagation()">+ Keranjang</button></div>
        </div>
        <div class="prod-info">
          <h3>CHRISBALE Urban Runner</h3>
        </div>
      </article>
      <article class="prod-card" onclick="window.location='/product/gold-leather-loafer'">
        <span class="badge-tag badge-hot">Populer</span>
        <div class="prod-img-wrap">
          <img src="https://images.unsplash.com/photo-1614252369475-531eba835eb1?w=600&q=80&auto=format&fit=crop" alt="Gold Leather Loafer" loading="lazy">
          <div class="prod-overlay"><button class="add-btn" onclick="event.stopPropagation()">+ Keranjang</button></div>
        </div>
        <div class="prod-info">
          <h3>Gold Leather Loafer</h3>
        </div>
      </article>
      <article class="prod-card" onclick="window.location='/product/combat-boot-black'">
        <span class="badge-tag badge-new">Baru</span>
        <div class="prod-img-wrap">
          <img src="https://images.unsplash.com/photo-1608256246200-53e635b5b65f?w=600&q=80&auto=format&fit=crop" alt="Combat Boot Black" loading="lazy">
          <div class="prod-overlay"><button class="add-btn" onclick="event.stopPropagation()">+ Keranjang</button></div>
        </div>
        <div class="prod-info">
          <h3>Combat Boot — Hitam</h3>
        </div>
      </article>
      <article class="prod-card" onclick="window.location='/product/classic-slip-on-white'">
        <span class="badge-tag badge-sale">-30%</span>
        <div class="prod-img-wrap">
          <img src="https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?w=600&q=80&auto=format&fit=crop" alt="Classic Slip-On White" loading="lazy">
          <div class="prod-overlay"><button class="add-btn" onclick="event.stopPropagation()">+ Keranjang</button></div>
        </div>
        <div class="prod-info">
          <h3>Classic Slip-On Putih</h3>
        </div>
      </article>
    </div>
  </div>
</section>

<!-- NEW ARRIVALS -->
<section class="section" style="padding-top:0;" id="new">
  <div class="wrap">
    <div class="section-head">
      <div class="section-head-left">
        <span class="eyebrow">Barang Baru</span>
        <h2>Fresh Drops Minggu Ini</h2>
        <p>Jadilah yang pertama mendapatkan gaya terbaru.</p>
      </div>
      <a href="#" class="section-link">Beli Baru</a>
    </div>
    <div class="grid-prod">
      <article class="prod-card" onclick="window.location='/product/red-strap-sandal'">
        <span class="badge-tag badge-new">Baru</span>
        <div class="prod-img-wrap">
          <img src="https://images.unsplash.com/photo-1603487742131-4160ec999306?w=600&q=80&auto=format&fit=crop" alt="Red Strap Sandal" loading="lazy">
          <div class="prod-overlay"><button class="add-btn" onclick="event.stopPropagation()">+ Keranjang</button></div>
        </div>
        <div class="prod-info">
          <h3>Red Strap Sandal</h3>
        </div>
      </article>
      <article class="prod-card" onclick="window.location='/product/navy-slide-sandal'">
        <span class="badge-tag badge-new">Baru</span>
        <div class="prod-img-wrap">
          <img src="https://images.unsplash.com/photo-1562183241-b937e9102f3b?w=600&q=80&auto=format&fit=crop" alt="Navy Slide Sandal" loading="lazy">
          <div class="prod-overlay"><button class="add-btn" onclick="event.stopPropagation()">+ Keranjang</button></div>
        </div>
        <div class="prod-info">
          <h3>Navy Slide Sandal</h3>
        </div>
      </article>
      <article class="prod-card" onclick="window.location='/product/brown-leather-mule'">
        <div class="prod-img-wrap">
          <img src="https://images.unsplash.com/photo-1516478177764-9fe5bd7e9717?w=600&q=80&auto=format&fit=crop" alt="Brown Leather Mule" loading="lazy">
          <div class="prod-overlay"><button class="add-btn" onclick="event.stopPropagation()">+ Keranjang</button></div>
        </div>
        <div class="prod-info">
          <h3>Brown Leather Mule</h3>
        </div>
      </article>
      <article class="prod-card" onclick="window.location='/product/canvas-high-top'">
        <span class="badge-tag badge-sale">-25%</span>
        <div class="prod-img-wrap">
          <img src="https://images.unsplash.com/photo-1460353581641-37baddab0fa2?w=600&q=80&auto=format&fit=crop" alt="Canvas High Top" loading="lazy">
          <div class="prod-overlay"><button class="add-btn" onclick="event.stopPropagation()">+ Keranjang</button></div>
        </div>
        <div class="prod-info">
          <h3>Canvas High Top</h3>
        </div>
      </article>
    </div>
  </div>
</section>

<!-- FEATURED BANNER -->
<section class="section" style="padding-top:0;" id="sale">
  <div class="wrap">
    <div class="featured-banner">
      <div class="featured-img-side">
        <img src="https://images.unsplash.com/photo-1539185441755-769473a23570?w=900&q=80&auto=format&fit=crop" alt="CHRISBALE Signature Collection">
      </div>
      <div class="featured-text">
        <span class="tag">Edisi Terbatas</span>
        <h2>CHRISBALE x Urban<br>Signature Collection</h2>
        <p>Sneaker premium buatan tangan dengan kulit Italia full-grain, insole memory foam, dan emblem emas khas kami. Hanya 500 pasang di seluruh dunia.</p>
        <div class="featured-meta">
          <div class="featured-meta-item">
            <div class="fm-num">500</div>
            <div class="fm-label">Pasang Saja</div>
          </div>
          <div class="featured-meta-item">
            <div class="fm-num">100%</div>
            <div class="fm-label">Kulit Asli</div>
          </div>
          <div class="featured-meta-item">
            <div class="fm-num">2th</div>
            <div class="fm-label">Garansi</div>
          </div>
        </div>
        <a href="#" class="btn-primary" style="width:fit-content;">
          Beli Koleksi
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
      </div>
    </div>
    <div class="featured-strip">
      <div class="strip-item">
        <div class="strip-item-img"><img src="https://images.unsplash.com/photo-1584735175315-9d5df23be1f2?w=500&q=80&auto=format&fit=crop" alt="Gold Edition" loading="lazy"></div>
        <div class="strip-item-info"><h4>Gold Edition</h4><span>Rp4.489.000</span></div>
      </div>
      <div class="strip-item">
        <div class="strip-item-img"><img src="https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?w=500&q=80&auto=format&fit=crop" alt="Midnight Black" loading="lazy"></div>
        <div class="strip-item-info"><h4>Midnight Black</h4><span>Rp3.889.000</span></div>
      </div>
      <div class="strip-item">
        <div class="strip-item-img"><img src="https://images.unsplash.com/photo-1491553895911-0055eca6402d?w=500&q=80&auto=format&fit=crop" alt="Cream Canvas" loading="lazy"></div>
        <div class="strip-item-info"><h4>Cream Canvas</h4><span>Rp2.089.000</span></div>
      </div>
      <div class="strip-item">
        <div class="strip-item-img"><img src="https://images.unsplash.com/photo-1543163521-1bf539c55dd2?w=500&q=80&auto=format&fit=crop" alt="Tan Chelsea" loading="lazy"></div>
        <div class="strip-item-info"><h4>Tan Chelsea</h4><span>Rp2.839.000</span></div>
      </div>
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section class="section" style="padding-top:0;">
  <div class="wrap">
    <div class="section-head">
      <div class="section-head-left">
        <span class="eyebrow">Testimoni</span>
        <h2>Kata Pelanggan Kami</h2>
      </div>
    </div>
    <div class="testi-scroll">
      <div class="testi-card">
        <div class="stars">
          <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        </div>
        <blockquote>"Kualitasnya tak tertandingi. Saya sudah lama mencari sneaker premium yang benar-benar awet — CHRISBALE jawabannya."</blockquote>
        <div class="author">Alex M. <span>&#10003; Pembeli Terverifikasi</span></div>
      </div>
      <div class="testi-card">
        <div class="stars">
          <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        </div>
        <blockquote>"Saya beli Gold Leather Loafers untuk pernikahan saya. Kriya yang sangat memukau. Sangat direkomendasikan!"</blockquote>
        <div class="author">Sarah J. <span>&#10003; Pembeli Terverifikasi</span></div>
      </div>
      <div class="testi-card">
        <div class="stars">
          <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        </div>
        <blockquote>"Pengiriman cepat dan Combat Boots-nya sangat nyaman langsung dari kotak. Tidak perlu masa penyesuaian."</blockquote>
        <div class="author">Marcus T. <span>&#10003; Pembeli Terverifikasi</span></div>
      </div>
      <div class="testi-card">
        <div class="stars">
          <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        </div>
        <blockquote>"Akhirnya merek sneaker yang terlihat premium tanpa harga yang berlebihan. Jadi andalan sehari-hari saya."</blockquote>
        <div class="author">Diana K. <span>&#10003; Pembeli Terverifikasi</span></div>
      </div>
    </div>
  </div>
</section>



@endsection

@push('scripts')
<script>
/* HERO CAROUSEL */
(function(){
  const INTERVAL    = 5000;
  const TRANSITION  = 800;
  const track     = document.getElementById('heroTrack');
  if(!track) return;
  const slides    = Array.from(track.querySelectorAll('.hero-slide'));
  const dots      = Array.from(document.querySelectorAll('.carousel-dot'));
  const counter   = document.getElementById('heroCounter');
  const progress  = document.getElementById('heroProgress');
  const prevBtn   = document.getElementById('heroPrev');
  const nextBtn   = document.getElementById('heroNext');
  const heroEl    = document.getElementById('heroCarousel');
  const total     = slides.length;
  let current     = 0;
  let timer       = null;
  let isAnimating = false;
  function pad(n){ return String(n+1).padStart(2,'0'); }
  function goTo(idx, fromUser){
    if(isAnimating) return;
    isAnimating = true;
    slides[current].classList.remove('is-active');
    dots[current].classList.remove('active');
    current = (idx + total) % total;
    track.style.transform = 'translateX(-' + (current * 100) + '%)';
    setTimeout(function(){ slides[current].classList.add('is-active'); isAnimating = false; }, TRANSITION * 0.4);
    dots[current].classList.add('active');
    counter.textContent = pad(current) + ' / ' + pad(total-1);
    resetProgress();
    if(!fromUser){ clearInterval(timer); timer = setInterval(function(){ goTo(current + 1); }, INTERVAL); }
  }
  function resetProgress(){
    progress.style.transition = 'none';
    progress.style.width = '0%';
    void progress.offsetWidth;
    progress.style.transition = 'width ' + INTERVAL + 'ms linear';
    progress.style.width = '100%';
  }
  function startAuto(){
    clearInterval(timer);
    timer = setInterval(function(){ goTo(current + 1); }, INTERVAL);
    resetProgress();
  }
  prevBtn.addEventListener('click',function(){ goTo(current - 1, true); startAuto(); });
  nextBtn.addEventListener('click',function(){ goTo(current + 1, true); startAuto(); });
  dots.forEach(function(dot, i){ dot.addEventListener('click',function(){ if(i !== current){ goTo(i, true); startAuto(); } }); });
  var touchStartX = 0;
  heroEl.addEventListener('touchstart', function(e){ touchStartX = e.changedTouches[0].clientX; }, {passive:true});
  heroEl.addEventListener('touchend', function(e){
    var diff = touchStartX - e.changedTouches[0].clientX;
    if(Math.abs(diff) > 50){ diff > 0 ? goTo(current + 1, true) : goTo(current - 1, true); startAuto(); }
  }, {passive:true});
  document.addEventListener('keydown', function(e){
    if(e.key === 'ArrowLeft')  { goTo(current - 1, true); startAuto(); }
    if(e.key === 'ArrowRight') { goTo(current + 1, true); startAuto(); }
  });
  slides[0].classList.add('is-active');
  counter.textContent = pad(0) + ' / ' + pad(total-1);
  startAuto();
})();


</script>
@endpush
