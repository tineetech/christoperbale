@extends('layouts.app')

@section('title', 'Kebijakan Privasi — CHRISBALE')

@section('content')

<section class="legal-section">
  <div class="legal-container">
    <div class="legal-head">
      <span class="legal-eyebrow">Legal</span>
      <h1>Kebijakan Privasi</h1>
      <p class="legal-date">Terakhir diperbarui: 20 July 2026</p>
    </div>

    <div class="legal-body">
      <h2>1. Informasi yang Kami Kumpulkan</h2>
      <p>Kami mengumpulkan informasi yang Anda berikan secara langsung saat mendaftar, melakukan pemesanan, atau menghubungi kami. Informasi tersebut dapat mencakup nama, alamat email, alamat pengiriman, nomor telepon, dan informasi pembayaran.</p>

      <h2>2. Penggunaan Informasi</h2>
      <p>Informasi yang kami kumpulkan digunakan untuk memproses pesanan, mengirimkan pembaruan status, meningkatkan layanan, dan mengirimkan penawaran promosi jika Anda memberikan izin.</p>

      <h2>3. Perlindungan Data</h2>
      <p>Kami menerapkan langkah-langkah keamanan teknis dan organisasi yang sesuai untuk melindungi informasi pribadi Anda dari akses tidak sah, perubahan, pengungkapan, atau penghancuran.</p>

      <h2>4. Cookie</h2>
      <p>Situs kami menggunakan cookie untuk meningkatkan pengalaman pengguna, menganalisis lalu lintas, dan menyesuaikan konten. Anda dapat mengontrol penggunaan cookie melalui pengaturan browser Anda.</p>

      <h2>5. Berbagi Data Pihak Ketiga</h2>
      <p>Kami tidak menjual, menukar, atau menyewakan informasi pribadi Anda kepada pihak ketiga. Kami dapat membagikan informasi dengan mitra tepercaya yang membantu kami mengoperasikan situs dan melayani Anda, dengan kewajiban kerahasiaan yang ketat.</p>

      <h2>6. Hak Anda</h2>
      <p>Anda berhak untuk mengakses, memperbaiki, atau menghapus data pribadi Anda kapan saja. Untuk melakukan hal ini, silakan hubungi kami melalui halaman <a href="/contact">Kontak</a>.</p>

      <h2>7. Perubahan Kebijakan</h2>
      <p>Kami dapat memperbarui kebijakan privasi ini dari waktu ke waktu. Setiap perubahan akan diumumkan di halaman ini dengan tanggal pembaruan yang tercantum di bagian atas.</p>

      <h2>8. Kontak</h2>
      <p>Jika Anda memiliki pertanyaan atau kekhawatiran mengenai kebijakan privasi ini, silakan hubungi kami melalui halaman <a href="/contact">Kontak</a>.</p>
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
