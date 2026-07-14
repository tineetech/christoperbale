@extends('layouts.app')

@section('title', 'Tentang Kami — CHRISBALE')

@section('content')

<!-- ABOUT HERO -->
<section class="about-hero">
  <div class="about-hero-bg">
    <div class="about-hero-bg-half">
      <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=900&q=80&auto=format&fit=crop" alt="">
    </div>
    <div class="about-hero-bg-half">
      <img src="https://images.unsplash.com/photo-1608256246200-53e635b5b65f?w=900&q=80&auto=format&fit=crop" alt="">
    </div>
  </div>
  <div class="about-hero-overlay"></div>
  <div class="about-hero-content">
    <span class="about-hero-tag">&#9679; Berdiri 2022 — Jakarta, Indonesia</span>
    <h1>Kami Membuat <em>Alas Kaki</em><br>yang Bergerak Bersama<br>Cerita Anda</h1>
    <p>Lahir dari passion untuk desain modern yang berakar pada kriya tradisional — setiap pasang sepatu CHRISBALE dibuat untuk dikenakan bertahun-tahun, bukan sekadar musiman.</p>
    <div class="about-hero-actions">
      <a href="#story" class="btn-primary">
        Temukan Cerita Kami
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
      </a>
      <a href="/products" class="btn-outline-hero">Beli Koleksi</a>
    </div>
  </div>
</section>

<!-- MARQUEE -->
<div class="marquee">
  <div class="marquee-inner marquee-animate">
    <span>Berdiri di Jakarta</span><span class="sep">✦</span>
    <span>Keahlian Premium</span><span class="sep">✦</span>
    <span>Bahan Berkelanjutan</span><span class="sep">✦</span>
    <span>5.000+ Pelanggan Puas</span><span class="sep">✦</span>
    <span>Kirim ke 15 Negara</span><span class="sep">✦</span>
    <span>Garansi 2 Tahun</span><span class="sep">✦</span>
    <span>Berdiri di Jakarta</span><span class="sep">✦</span>
    <span>Keahlian Premium</span><span class="sep">✦</span>
    <span>Bahan Berkelanjutan</span><span class="sep">✦</span>
    <span>5.000+ Pelanggan Puas</span><span class="sep">✦</span>
    <span>Kirim ke 15 Negara</span><span class="sep">✦</span>
    <span>Garansi 2 Tahun</span><span class="sep">✦</span>
  </div>
</div>

<!-- STATS BAR -->
<div class="stats-bar">
  <div class="wrap">
    <div class="stats-bar-inner">
      <div class="stat-cell reveal">
        <div class="num" data-count="5000">0</div>
        <div class="label">Pelanggan Puas</div>
      </div>
      <div class="stat-cell reveal reveal-delay-1">
        <div class="num" data-count="200">0</div>
        <div class="label">Model Dirilis</div>
      </div>
      <div class="stat-cell reveal reveal-delay-2">
        <div class="num" data-count="15">0</div>
        <div class="label">Negara Tujuan</div>
      </div>
      <div class="stat-cell reveal reveal-delay-3">
        <div class="num">2yr</div>
        <div class="label">Garansi Produk</div>
      </div>
    </div>
  </div>
</div>

<!-- OUR STORY -->
<section class="section" id="story">
  <div class="wrap">
    <div class="story-grid reveal">
      <div class="story-img-wrap">
        <img src="https://images.unsplash.com/photo-1608256246200-53e635b5b65f?w=800&q=80&auto=format&fit=crop" alt="CHRISBALE Story">
        <div class="story-img-badge">
          <div class="badge-label">Berdiri</div>
          <div class="badge-val">2022</div>
        </div>
      </div>
      <div class="story-text">
        <div class="eyebrow">Cerita Kami</div>
        <h2>Merek yang Lahir dari<br>Kecintaan pada Kriya</h2>
        <p>CHRISBALE didirikan dengan keyakinan sederhana — bahwa sepatu berkualitas tidak harus mengorbankan kenyamanan demi gaya. Berawal dari proyek kecil di Jakarta, kini telah menjadi merek alas kaki yang dipercaya ribuan orang di seluruh Asia Tenggara dan sekitarnya.</p>
        <p>Setiap pasang dirancang sendiri dan diproduksi dalam jumlah terbatas menggunakan kulit full-grain, suede premium, dan bahan berkelanjutan dari penyamak kulit terpercaya. Kami percaya pada slow fashion — membuat lebih sedikit, namun lebih baik.</p>
        <div class="value-pills">
          <span class="value-pill"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Slow Fashion</span>
          <span class="value-pill"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Bahan Etis</span>
          <span class="value-pill"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Produksi Terbatas</span>
          <span class="value-pill"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Tahan Lama</span>
        </div>
        <div class="signature">— Tim CHRISBALE</div>
      </div>
    </div>
  </div>
</section>

<!-- INTERACTIVE TABS — Our Values -->
<section class="tabs-section">
  <div class="wrap">
    <div class="section-head reveal" style="margin-bottom:0;">
      <div>
        <span class="eyebrow">Nilai Kami</span>
        <h2>Apa yang Kami Perjuangkan</h2>
      </div>
    </div>
    <div class="tabs-nav" id="tabsNav">
      <button class="tab-btn active" data-tab="craft">Keahlian</button>
      <button class="tab-btn" data-tab="sustain">Keberlanjutan</button>
      <button class="tab-btn" data-tab="comfort">Kenyamanan</button>
      <button class="tab-btn" data-tab="community">Komunitas</button>
    </div>

    <div class="tab-panel active" data-panel="craft">
      <div class="tab-img">
        <img src="https://images.unsplash.com/photo-1550345332-09e3ac987658?w=800&q=80&auto=format&fit=crop" alt="Craftsmanship">
      </div>
      <div class="tab-content">
        <div class="eyebrow">Keahlian</div>
        <h3>Generasi Keahlian<br>dalam Setiap Jahitan</h3>
        <p>Kami tidak membuat dengan cepat. Kami bekerja dengan workshop keluarga yang telah menguasai kriya mereka selama puluhan tahun. Setiap jahitan, setiap potongan, setiap finishing tepi adalah tindakan kualitas yang disengaja.</p>
        <div class="tab-list">
          <div class="tab-list-item"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Upper dijahit tangan dengan penguat ujung kaki</div>
          <div class="tab-list-item"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Tepi finishing tangan diwarnai sesuai upper</div>
          <div class="tab-list-item"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Inspeksi 27 titik kualitas pada setiap pasang</div>
          <div class="tab-list-item"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Produksi batch kecil — tidak pernah terburu-buru</div>
        </div>
      </div>
    </div>

    <div class="tab-panel" data-panel="sustain">
      <div class="tab-img">
        <img src="https://images.unsplash.com/photo-1491553895911-0055eca6402d?w=800&q=80&auto=format&fit=crop" alt="Sustainability">
      </div>
      <div class="tab-content">
        <div class="eyebrow">Keberlanjutan</div>
        <h3>Dibuat untuk Tahan Lama,<br>Bukan untuk TPA</h3>
        <p>Kami percaya sepatu yang paling berkelanjutan adalah yang Anda pakai bertahun-tahun. Itulah mengapa kami berinvestasi pada bahan yang menua dengan indah dan konstruksi yang tahan pemakaian sehari-hari.</p>
        <div class="tab-list">
          <div class="tab-list-item"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Penyamak bersertifikat dengan penggunaan air yang bertanggung jawab</div>
          <div class="tab-list-item"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Kemasan minimal dan daur ulang pada setiap pesanan</div>
          <div class="tab-list-item"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Program perbaikan sol & upper untuk pelanggan setia</div>
          <div class="tab-list-item"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Inisiatif eco-tanning 2026 segera hadir</div>
        </div>
      </div>
    </div>

    <div class="tab-panel" data-panel="comfort">
      <div class="tab-img">
        <img src="https://images.unsplash.com/photo-1539185441755-769473a23570?w=800&q=80&auto=format&fit=crop" alt="Comfort">
      </div>
      <div class="tab-content">
        <div class="eyebrow">Kenyamanan</div>
        <h3>Kenyamanan Premium,<br>Sejak Langkah Pertama</h3>
        <p>Tidak perlu masa penyesuaian. Tidak ada kompromi. Kami merancang setiap insole dan cetakan untuk memberikan kenyamanan instan — karena sepatu yang hebat harus terasa senyaman penampilannya.</p>
        <div class="tab-list">
          <div class="tab-list-item"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Sistem insole memory foam khas kami</div>
          <div class="tab-list-item"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Cetakan anatomis untuk postur kaki alami</div>
          <div class="tab-list-item"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Lapisan kulit bernapas pada semua model tertutup</div>
          <div class="tab-list-item"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Sol luar karet anti-selip teruji untuk permukaan basah</div>
        </div>
      </div>
    </div>

    <div class="tab-panel" data-panel="community">
      <div class="tab-img">
        <img src="https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?w=800&q=80&auto=format&fit=crop" alt="Community">
      </div>
      <div class="tab-content">
        <div class="eyebrow">Komunitas</div>
        <h3>Dibangun oleh Orang,<br>Dipakai oleh Orang</h3>
        <p>CHRISBALE bermula sebagai komunitas. Pelanggan kami adalah kolaborator — mereka memilih varian warna yang akan datang, menguji prototipe, dan membantu membentuk apa yang kami buat selanjutnya.</p>
        <div class="tab-list">
          <div class="tab-list-item"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Akses awal eksklusif anggota untuk koleksi baru</div>
          <div class="tab-list-item"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Voting varian warna komunitas setiap kuartal</div>
          <div class="tab-list-item"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Hadiah rujukan untuk pendukung setia</div>
          <div class="tab-list-item"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Program umpan balik prototipe untuk pelanggan terbaik</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- OUR CRAFT — Story Block 2 -->
<section class="section">
  <div class="wrap">
    <div class="story-grid reverse reveal">
      <div class="story-img-wrap">
        <img src="https://images.unsplash.com/photo-1584735175315-9d5df23be1f2?w=800&q=80&auto=format&fit=crop" alt="CHRISBALE Workshop">
        <div class="story-img-badge">
          <div class="badge-label">Workshop</div>
          <div class="badge-val">Bandung</div>
        </div>
      </div>
      <div class="story-text">
        <div class="eyebrow">Workshop Kami</div>
        <h2>Tempat Setiap Pasang<br>Menjadi Hidup</h2>
        <p>Dari sketsa ke sol, setiap desain menjalani pengujian ketat sebelum mencapai mitra workshop kami. Kami bekerja secara eksklusif dengan atelier keluarga yang memiliki obsesi yang sama terhadap detail.</p>
        <p>Insole memory foam khas kami, jahitan diperkuat, dan tepi finishing tangan memastikan setiap pasang sepatu CHRISBALE terasa senyaman penampilannya — dipakai terus, tahun demi tahun.</p>
        <div class="value-pills">
          <span class="value-pill"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Pengrajin Bandung</span>
          <span class="value-pill"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Pengalaman 30+ Tahun</span>
          <span class="value-pill"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Finishing Tangan</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section class="section" style="padding-top:0;">
  <div class="wrap">
    <div class="section-head reveal">
      <div>
        <span class="eyebrow">Testimoni</span>
        <h2>Dipakai & Disukai</h2>
      </div>
    </div>
    <div class="testi-scroll">
      <div class="testi-card">
        <div class="stars"><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div>
        <blockquote>"Kualitasnya tak tertandingi. Saya sudah lama mencari sepatu premium yang benar-benar awet — CHRISBALE jawabannya."</blockquote>
        <div class="author">Alex M. <span>&#10003; Pembeli Terverifikasi</span></div>
      </div>
      <div class="testi-card">
        <div class="stars"><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div>
        <blockquote>"Membeli Gold Leather Loafers untuk pernikahan saya. Keahlian yang sungguh menakjubkan. Sangat direkomendasikan!"</blockquote>
        <div class="author">Sarah J. <span>&#10003; Pembeli Terverifikasi</span></div>
      </div>
      <div class="testi-card">
        <div class="stars"><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div>
        <blockquote>"Combat Boots sangat nyaman langsung dari kotak. Tidak perlu masa penyesuaian. Ini sepatu yang benar-benar berkualitas."</blockquote>
        <div class="author">Marcus T. <span>&#10003; Pembeli Terverifikasi</span></div>
      </div>
      <div class="testi-card">
        <div class="stars"><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div>
        <blockquote>"Akhirnya merek alas kaki yang terlihat premium tanpa harga yang berlebihan. Pesan dua kali, dipakai setiap hari, tidak ada keluhan."</blockquote>
        <div class="author">Diana K. <span>&#10003; Pembeli Terverifikasi</span></div>
      </div>
    </div>
  </div>
</section>

<!-- FAQ ACCORDION -->
<section class="section" style="padding-top:0;">
  <div class="wrap">
    <div class="section-head reveal" style="margin-bottom:32px;">
      <div>
        <span class="eyebrow">FAQ</span>
        <h2>Pertanyaan Umum</h2>
      </div>
    </div>
    <div class="accordion reveal">
      <div class="accordion-item">
        <button class="accordion-btn" data-acc>
          Di mana sepatu CHRISBALE dibuat?
          <svg class="accordion-icon" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        </button>
        <div class="accordion-body">
          <div class="accordion-body-inner">Sepatu kami dirancang di Jakarta dan dibuat dengan tangan di workshop mitra kami di Bandung, Jawa Barat — atelier keluarga dengan pengalaman lebih dari 30 tahun dalam pembuatan sepatu.</div>
        </div>
      </div>
      <div class="accordion-item">
        <button class="accordion-btn" data-acc>
          Bahan apa yang Anda gunakan?
          <svg class="accordion-icon" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        </button>
        <div class="accordion-body">
          <div class="accordion-body-inner">Kami menggunakan kulit full-grain dan suede premium dari penyamak bersertifikat di Italia dan Amerika Selatan. Insole dilengkapi lapisan memory foam khas kami untuk kenyamanan instan. Semua bahan diperoleh secara etis.</div>
        </div>
      </div>
      <div class="accordion-item">
        <button class="accordion-btn" data-acc>
          Berapa lama pengiriman?
          <svg class="accordion-icon" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        </button>
        <div class="accordion-body">
          <div class="accordion-body-inner">Pesanan domestik (Indonesia) tiba dalam 2–4 hari kerja. Pesanan internasional dikirim ke 15+ negara dan biasanya memakan waktu 7–14 hari kerja. Gratis ongkir untuk pembelian di atas Rp2.000.000.</div>
        </div>
      </div>
      <div class="accordion-item">
        <button class="accordion-btn" data-acc>
          Apakah Anda menerima retur atau penukaran?
          <svg class="accordion-icon" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        </button>
        <div class="accordion-body">
          <div class="accordion-body-inner">Ya — kami menawarkan kebijakan retur dan penukaran tanpa ribet selama 30 hari. Barang harus tidak dipakai dan dalam kemasan asli. Hubungi kami di hello@chrisbale.com untuk memulai pengembalian.</div>
        </div>
      </div>
      <div class="accordion-item">
        <button class="accordion-btn" data-acc>
          Apa saja yang dicakup garansi 2 tahun?
          <svg class="accordion-icon" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        </button>
        <div class="accordion-body">
          <div class="accordion-body-inner">Garansi 2 tahun kami mencakup cacat produksi termasuk pemisahan sol, kegagalan jahitan, dan masalah aksesori. Keausan normal atau kerusakan akibat penyalahgunaan tidak dicakup. Kami juga menawarkan layanan perbaikan berbayar untuk pasangan di luar garansi.</div>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection

@push('scripts')
<script>
/* ── SCROLL REVEAL ── */
const reveals = document.querySelectorAll('.reveal');
const revealObs = new IntersectionObserver((entries)=>{
  entries.forEach(e=>{if(e.isIntersecting){e.target.classList.add('visible');}});
},{threshold:0.12});
reveals.forEach(el=>revealObs.observe(el));

/* ── COUNT-UP ANIMATION ── */
function countUp(el,target,duration){
  let start=0;
  const step = Math.ceil(target/60);
  const suffix = target>=1000?'+':'';
  const timer = setInterval(()=>{
    start+=step;
    if(start>=target){start=target;clearInterval(timer);}
    el.textContent = start.toLocaleString()+suffix;
  },duration/60);
}
const statObs = new IntersectionObserver((entries)=>{
  entries.forEach(e=>{
    if(e.isIntersecting){
      const el=e.target;
      const val=parseInt(el.dataset.count);
      if(val) countUp(el,val,1200);
      statObs.unobserve(el);
    }
  });
},{threshold:0.5});
document.querySelectorAll('[data-count]').forEach(el=>statObs.observe(el));

/* ── TABS ── */
const tabBtns   = document.querySelectorAll('.tab-btn');
const tabPanels = document.querySelectorAll('.tab-panel');
tabBtns.forEach(btn=>{
  btn.addEventListener('click',()=>{
    const target = btn.dataset.tab;
    tabBtns.forEach(b=>b.classList.remove('active'));
    tabPanels.forEach(p=>p.classList.remove('active'));
    btn.classList.add('active');
    document.querySelector(`[data-panel="${target}"]`).classList.add('active');
  });
});

/* ── ACCORDION ── */
document.querySelectorAll('[data-acc]').forEach(btn=>{
  btn.addEventListener('click',()=>{
    const body = btn.nextElementSibling;
    const isOpen = btn.classList.contains('open');
    document.querySelectorAll('[data-acc]').forEach(b=>{
      b.classList.remove('open');
      b.nextElementSibling.classList.remove('open');
    });
    if(!isOpen){
      btn.classList.add('open');
      body.classList.add('open');
    }
  });
});
</script>
@endpush
