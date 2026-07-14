@extends('layouts.app')

@section('title', 'Syarat & Ketentuan — CHRISBALE')

@section('content')

<section class="legal-section">
  <div class="legal-container">
    <div class="legal-head">
      <span class="legal-eyebrow">Legal</span>
      <h1>Syarat & Ketentuan</h1>
      <p class="legal-date">Terakhir diperbarui: 20 July 2026</p>
    </div>

    <div class="legal-body">
      <h2>1. Pendahuluan</h2>
      <p>Selamat datang di CHRISBALE. Dengan mengakses dan menggunakan situs ini, Anda menyetujui untuk terikat oleh syarat dan ketentuan yang dijelaskan di halaman ini.</p>

      <h2>2. Penggunaan Situs</h2>
      <p>Anda setuju untuk menggunakan situs ini hanya untuk tujuan yang sah dan sesuai dengan hukum yang berlaku. Dilarang menggunakan situs ini untuk aktivitas yang melanggar hukum atau melanggar hak pihak ketiga.</p>

      <h2>3. Produk & Pemesanan</h2>
      <p>Semua produk yang ditampilkan di situs ini tersedia selama persediaan masih ada. Harga dapat berubah sewaktu-waktu tanpa pemberitahuan sebelumnya. Setiap pemesanan yang dilakukan melalui situs ini tunduk pada konfirmasi dari kami.</p>

      <h2>4. Kebijakan Pengembalian</h2>
      <p>Pengembalian barang dapat dilakukan dalam waktu 14 hari setelah barang diterima, dengan syarat barang dalam kondisi asli dan belum dipakai. Biaya pengiriman pengembalian ditanggung oleh pembeli kecuali ada cacat produksi.</p>

      <h2>5. Kekayaan Intelektual</h2>
      <p>Seluruh konten di situs ini, termasuk teks, gambar, logo, dan desain, adalah milik CHRISBALE dan dilindungi oleh undang-undang hak cipta. Dilarang menggunakan konten tanpa izin tertulis.</p>

      <h2>6. Batasan Tanggung Jawab</h2>
      <p>CHRISBALE tidak bertanggung jawab atas kerugian langsung atau tidak langsung yang timbul dari penggunaan situs ini, sejauh diizinkan oleh hukum yang berlaku.</p>

      <h2>7. Perubahan</h2>
      <p>Kami berhak mengubah syarat dan ketentuan ini kapan saja. Perubahan akan diumumkan di halaman ini dan berlaku segera setelah dipublikasikan.</p>

      <h2>8. Kontak</h2>
      <p>Jika Anda memiliki pertanyaan mengenai syarat dan ketentuan ini, silakan hubungi kami melalui halaman <a href="/contact">Kontak</a>.</p>
    </div>
  </div>
</section>

<style>
.legal-section{padding:80px 20px;max-width:820px;margin:0 auto;}
.legal-head{text-align:center;margin-bottom:56px;}
.legal-eyebrow{display:inline-block;font-size:11px;letter-spacing:0.2em;text-transform:uppercase;color:var(--ink-muted);font-weight:500;margin-bottom:10px;}
.legal-head h1{font-size:clamp(24px,3vw,36px);color:var(--ink);margin-bottom:10px;}
.legal-date{font-size:13px;color:var(--ink-muted);}
.legal-body h2{font-size:16px;color:var(--ink);margin-top:36px;margin-bottom:10px;letter-spacing:0.02em;}
.legal-body p{font-size:14px;color:var(--ink-soft);line-height:1.8;margin-bottom:12px;}
.legal-body a{color:var(--accent);border-bottom:1px solid rgba(184,134,11,0.3);padding-bottom:1px;text-decoration:none;}
.legal-body a:hover{color:var(--accent-dark);border-bottom-color:var(--accent-dark);}
@media(max-width:600px){.legal-section{padding:48px 16px;}}
</style>

@endsection
