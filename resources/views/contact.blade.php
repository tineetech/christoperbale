@extends('layouts.app')

@section('title', 'Hubungi Kami — CHRISBALE')

@push('styles')
<style>
.parallax-head{position:relative;overflow:hidden;padding:100px 0;}
.parallax-bg{position:absolute;inset:0;}
.parallax-bg img{width:100%;height:100%;object-fit:cover;}
.parallax-overlay{position:absolute;inset:0;background:linear-gradient(135deg,rgba(10,8,6,0.85) 0%,rgba(10,8,6,0.65) 50%,rgba(10,8,6,0.8) 100%);}
@media(max-width:768px){.parallax-head{padding:70px 0;}}
</style>
@endpush

@section('content')

<section class="page-head parallax-head">
  <div class="parallax-bg"><img src="https://images.unsplash.com/photo-1516478177764-9fe5bd7e9717?w=1400&q=80&auto=format&fit=crop" alt="" id="parallaxImg"></div>
  <div class="parallax-overlay"></div>
  <div class="wrap" style="position:relative;z-index:2;">
    <h1>Hubungi Kami</h1>
    <p>Punya pertanyaan, masukan, atau hanya ingin menyapa? Kami senang mendengar dari Anda.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="contact-grid">
      <div class="contact-info">
        <h2>Mari Bicara</h2>
        <p>Tim kami siap membantu. Hubungi melalui salah satu saluran di bawah, atau isi formulir dan kami akan membalas dalam 24 jam.</p>
        <div class="info-item">
          <div class="info-icon">
            <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          </div>
          <div class="info-text"><h4>Email</h4><span>hello@chrisbale.com</span></div>
        </div>
        <div class="info-item">
          <div class="info-icon">
            <svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          </div>
          <div class="info-text"><h4>Telepon</h4><span>+62 812 3456 7890</span></div>
        </div>
        <div class="info-item">
          <div class="info-icon">
            <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
          </div>
          <div class="info-text"><h4>Lokasi</h4><span>Jakarta, Indonesia</span></div>
        </div>
        <div class="info-item">
          <div class="info-icon">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          </div>
          <div class="info-text"><h4>Jam Operasional</h4><span>Sen — Jum, 09:00 — 18:00</span></div>
        </div>
      </div>
      <form class="contact-form" id="contactForm">
        <div class="form-group">
          <label for="name">Nama Lengkap</label>
          <input type="text" id="name" name="name" placeholder="John Doe" required>
        </div>
        <div class="form-group">
          <label for="email">Alamat Email</label>
          <input type="email" id="email" name="email" placeholder="john@example.com" required>
        </div>
        <div class="form-group">
          <label for="subject">Subjek</label>
          <select id="subject" name="subject">
            <option>Pertanyaan Umum</option>
            <option>Bantuan Pesanan</option>
            <option>Retur & Penukaran</option>
            <option>Grosir</option>
            <option>Kemitraan</option>
          </select>
        </div>
        <div class="form-group">
          <label for="message">Pesan</label>
          <textarea id="message" name="message" placeholder="Beri tahu kami bagaimana kami dapat membantu..." required></textarea>
        </div>
        <button type="submit" class="btn-submit">Kirim Pesan</button>
      </form>
    </div>
  </div>
</section>

@endsection

@push('scripts')
<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
  e.preventDefault();
  var name = document.getElementById('name').value.trim();
  var email = document.getElementById('email').value.trim();
  var subject = document.getElementById('subject').value;
  var message = document.getElementById('message').value.trim();
  var text = 'Halo CHRISBALE,%0A%0A';
  text += '*Nama:* ' + encodeURIComponent(name) + '%0A';
  text += '*Email:* ' + encodeURIComponent(email) + '%0A';
  text += '*Subjek:* ' + encodeURIComponent(subject) + '%0A';
  text += '*Pesan:* ' + encodeURIComponent(message);
  window.open('https://wa.me/6281515145152?text=' + text, '_blank');
});
</script>
@endpush
