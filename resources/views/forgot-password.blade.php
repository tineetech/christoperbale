@extends('layouts.app')

@section('title', 'Lupa Password — CHRISBALE')


@push('styles')
  <style>
    /* =============================================
   FORGOT PASSWORD PAGE
   ============================================= */

.forgot-page {
  min-height: 100vh;
  background: var(--bg);
}

.forgot-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  min-height: 100vh;
}

/* =============================================
   LEFT SIDE
   ============================================= */

.forgot-left {
  position: relative;
  overflow: hidden;
  background: #0F0D0B;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.forgot-left-bg {
  position: absolute;
  inset: 0;
  overflow: hidden;
}

.forgot-left-bg img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
  filter: grayscale(30%);
  opacity: 0.45;
  transform: scale(1.05);
  transition: transform 8s ease;
}

.forgot-left:hover .forgot-left-bg img {
  transform: scale(1);
}

.forgot-left-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(
    to bottom,
    rgba(15, 13, 11, 0.55) 0%,
    rgba(15, 13, 11, 0.3) 40%,
    rgba(15, 13, 11, 0.85) 100%
  );
  z-index: 1;
}

.forgot-left-logo {
  position: relative;
  z-index: 2;
  padding: 36px 44px;
}

.forgot-left-logo .logo {
  color: #fff;
  font-size: 22px;
  letter-spacing: 0.1em;
}

.forgot-left-logo .logo span {
  color: var(--accent-light);
}

.forgot-left-content {
  position: relative;
  z-index: 2;
  padding: 44px;
}

.forgot-left-tag {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  border: 1px solid rgba(255, 255, 255, 0.25);
  padding: 5px 14px;
  border-radius: 999px;
  font-size: 10.5px;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: rgba(255, 255, 255, 0.65);
  margin-bottom: 18px;
  backdrop-filter: blur(4px);
}

.forgot-left-content h2 {
  font-size: clamp(22px, 2.8vw, 34px);
  color: #fff;
  line-height: 1.2;
  margin-bottom: 12px;
  letter-spacing: 0.01em;
  text-shadow: 0 2px 16px rgba(0,0,0,0.5);
}

.forgot-left-content h2 em {
  color: var(--accent-light);
  font-style: normal;
}

.forgot-left-content > p {
  color: rgba(255, 255, 255, 0.55);
  font-size: 13.5px;
  line-height: 1.75;
  margin-bottom: 28px;
  max-width: 360px;
}

.forgot-left-stats {
  display: flex;
  align-items: center;
  gap: 20px;
  margin-bottom: 28px;
  padding: 18px 22px;
  background: rgba(255, 255, 255, 0.07);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: var(--radius);
  backdrop-filter: blur(8px);
}

.forgot-stat {
  text-align: center;
  flex: 1;
}

.forgot-stat-num {
  font-family: 'Playfair Display', serif;
  font-size: 22px;
  font-weight: 700;
  color: #fff;
  line-height: 1;
  margin-bottom: 4px;
}

.forgot-stat-label {
  font-size: 10px;
  color: rgba(255, 255, 255, 0.45);
  letter-spacing: 0.1em;
  text-transform: uppercase;
}

.forgot-stat-divider {
  width: 1px;
  height: 36px;
  background: rgba(255, 255, 255, 0.12);
  flex-shrink: 0;
}

.forgot-left-testi {
  padding: 20px 22px;
  background: rgba(184, 134, 11, 0.12);
  border: 1px solid rgba(184, 134, 11, 0.25);
  border-radius: var(--radius);
  backdrop-filter: blur(6px);
}

.forgot-testi-stars {
  display: flex;
  gap: 3px;
  margin-bottom: 10px;
}

.forgot-testi-stars svg {
  width: 12px;
  height: 12px;
  fill: var(--accent-light);
  color: var(--accent-light);
}

.forgot-left-testi p {
  font-size: 13px;
  color: rgba(255, 255, 255, 0.75);
  line-height: 1.65;
  font-style: italic;
  font-family: 'Playfair Display', serif;
  margin-bottom: 8px;
}

.forgot-testi-author {
  font-size: 11px;
  color: rgba(255, 255, 255, 0.4);
  letter-spacing: 0.04em;
}

/* =============================================
   RIGHT SIDE
   ============================================= */

.forgot-right {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 48px 24px;
  background: var(--bg);
  min-height: 100vh;
  overflow-y: auto;
}

.forgot-form-wrap {
  width: 100%;
  max-width: 440px;
}

.forgot-mobile-logo {
  display: none;
  text-align: center;
  margin-bottom: 28px;
}

.forgot-mobile-logo .logo {
  font-size: 24px;
  letter-spacing: 0.1em;
  color: var(--ink);
}

.forgot-mobile-logo .logo span {
  color: var(--accent);
}

.forgot-form-header {
  margin-bottom: 28px;
}

.forgot-form-header .eyebrow {
  font-size: 11px;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  color: var(--ink-muted);
  font-weight: 500;
  display: block;
  margin-bottom: 8px;
}

.forgot-form-header h1 {
  font-size: clamp(22px, 2.5vw, 30px);
  color: var(--ink);
  margin-bottom: 10px;
  letter-spacing: 0.01em;
}

.forgot-form-header p {
  font-size: 13.5px;
  color: var(--ink-soft);
}

.forgot-link {
  color: var(--accent);
  font-weight: 500;
  text-decoration: none;
  transition: color 0.2s;
  border-bottom: 1px solid rgba(184, 134, 11, 0.3);
  padding-bottom: 1px;
}

.forgot-link:hover {
  color: var(--accent-dark);
  border-bottom-color: var(--accent-dark);
}

/* Alerts */
.forgot-alert {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  padding: 12px 16px;
  border-radius: var(--radius-sm);
  font-size: 13px;
  margin-bottom: 20px;
  line-height: 1.5;
}

.forgot-alert svg {
  width: 16px;
  height: 16px;
  flex-shrink: 0;
  margin-top: 1px;
}

.forgot-alert-error {
  background: rgba(192, 57, 43, 0.08);
  border: 1px solid rgba(192, 57, 43, 0.2);
  color: #C0392B;
}

.forgot-alert-error svg {
  stroke: #C0392B;
}

.forgot-alert-info {
  background: rgba(184, 134, 11, 0.07);
  border: 1px solid rgba(184, 134, 11, 0.2);
  color: var(--ink-soft);
}

.forgot-alert-info svg {
  stroke: var(--accent);
}

.forgot-alert-success {
  background: rgba(39, 174, 96, 0.08);
  border: 1px solid rgba(39, 174, 96, 0.2);
  color: #27AE60;
}

.forgot-alert-success svg {
  stroke: #27AE60;
}

/* Form */
.forgot-form {
  display: flex;
  flex-direction: column;
  gap: 0;
}

.forgot-field {
  margin-bottom: 18px;
}

.forgot-field label {
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: 12.5px;
  font-weight: 500;
  color: var(--ink-soft);
  margin-bottom: 8px;
  letter-spacing: 0.03em;
}

.forgot-input-wrap {
  position: relative;
  display: flex;
  align-items: center;
  border: 1.5px solid var(--line);
  border-radius: var(--radius-sm);
  background: var(--bg-card);
  transition: border-color 0.2s, box-shadow 0.2s;
  overflow: hidden;
}

.forgot-input-wrap.focused {
  border-color: var(--accent);
  box-shadow: 0 0 0 3px rgba(184, 134, 11, 0.1);
}

.forgot-input-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 46px;
  height: 100%;
  flex-shrink: 0;
  pointer-events: none;
  padding: 0 4px;
}

.forgot-input-icon svg {
  width: 16px;
  height: 16px;
  stroke: var(--ink-muted);
  flex-shrink: 0;
}

.forgot-input-wrap.focused .forgot-input-icon svg {
  stroke: var(--accent);
}

.forgot-input-wrap input {
  flex: 1;
  border: none;
  outline: none;
  padding: 13px 12px 13px 0;
  font-size: 13.5px;
  font-family: 'Inter', sans-serif;
  color: var(--ink);
  background: transparent;
  width: 100%;
}

.forgot-input-wrap input::placeholder {
  color: var(--ink-muted);
  font-weight: 300;
}

.forgot-field-error {
  display: block;
  font-size: 11.5px;
  color: #C0392B;
  margin-top: 6px;
  letter-spacing: 0.02em;
}

.forgot-submit-btn {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  background: var(--accent);
  color: #fff;
  border: none;
  border-radius: var(--radius-sm);
  padding: 15px 24px;
  font-size: 13px;
  font-weight: 500;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  cursor: pointer;
  transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
  margin-bottom: 24px;
  position: relative;
  overflow: hidden;
}

.forgot-submit-btn::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 60%);
}

.forgot-submit-btn:hover {
  background: var(--accent-dark);
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(184, 134, 11, 0.3);
}

.forgot-submit-btn:active {
  transform: translateY(0);
}

.forgot-submit-btn svg {
  width: 14px;
  height: 14px;
  flex-shrink: 0;
}

.forgot-form-footer {
  text-align: center;
  padding-top: 8px;
  padding-bottom: 20px;
  border-top: 1px solid var(--line-soft);
  margin-top: 4px;
}

.forgot-form-footer p {
  font-size: 11.5px;
  color: var(--ink-muted);
  line-height: 1.65;
}

.forgot-trust {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 20px;
  padding-top: 16px;
  flex-wrap: wrap;
}

.forgot-trust-item {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 11px;
  color: var(--ink-muted);
  letter-spacing: 0.04em;
}

.forgot-trust-item svg {
  width: 13px;
  height: 13px;
  stroke: var(--ink-muted);
  flex-shrink: 0;
}

.forgot-trust-sep {
  width: 1px;
  height: 14px;
  background: var(--line);
}

/* =============================================
   RESPONSIVE
   ============================================= */

@media (max-width: 1024px) {
  .forgot-left-content { padding: 36px; }
  .forgot-left-logo { padding: 28px 36px; }
  .forgot-left-content h2 { font-size: 26px; }
}

@media (max-width: 768px) {
  .forgot-grid { grid-template-columns: 1fr; min-height: 100vh; }
  .forgot-left { display: none; }
  .forgot-right {
    min-height: 100vh;
    padding: 32px 20px;
    align-items: flex-start;
    padding-top: 48px;
  }
  .forgot-form-wrap { max-width: 100%; }
  .forgot-mobile-logo { display: block; }
  .forgot-form-header { text-align: center; }
  .forgot-form-header p { text-align: center; }
  .forgot-submit-btn { padding: 16px 24px; }
  .forgot-trust { gap: 12px; }
  .forgot-trust-sep { display: none; }
}

@media (max-width: 400px) {
  .forgot-right { padding: 32px 16px; padding-top: 40px; }
  .forgot-form-header h1 { font-size: 22px; }
  .forgot-stat-num { font-size: 18px; }
  .forgot-trust { flex-direction: column; gap: 8px; }
}

@media (max-width: 768px) and (orientation: landscape) {
  .forgot-right { padding: 24px 20px; align-items: center; }
}

@media (min-width: 1440px) {
  .forgot-left-content { padding: 52px; }
  .forgot-left-logo { padding: 40px 52px; }
  .forgot-form-wrap { max-width: 480px; }
}
  </style>
@endpush

@section('content')

<div class="forgot-page">
  <div class="forgot-grid">

    <!-- LEFT SIDE -->
    <div class="forgot-left">
      <div class="forgot-left-bg">
        <img 
          src="https://images.unsplash.com/photo-1491553895911-0055eca6402d?w=900&q=80&auto=format&fit=crop" 
          alt="CHRISBALE Premium Footwear"
        >
      </div>
      <div class="forgot-left-overlay"></div>

      <div class="forgot-left-logo">
        <a href="/" class="logo">CHRIS<span>BALE</span></a>
      </div>

      <div class="forgot-left-content">
        <h2>Tenang, Kami <em>Bantu</em> Anda</h2>
        <p>Jangan khawatir jika Anda lupa password. Masukkan email Anda dan kami akan kirimkan tautan untuk meresetnya.</p>

        <div class="forgot-left-stats">
          <div class="forgot-stat">
            <div class="forgot-stat-num">50K+</div>
            <div class="forgot-stat-label">Pelanggan</div>
          </div>
          <div class="forgot-stat-divider"></div>
          <div class="forgot-stat">
            <div class="forgot-stat-num">500+</div>
            <div class="forgot-stat-label">Koleksi</div>
          </div>
          <div class="forgot-stat-divider"></div>
          <div class="forgot-stat">
            <div class="forgot-stat-num">4.9</div>
            <div class="forgot-stat-label">Rating</div>
          </div>
        </div>

        <div class="forgot-left-testi">
          <div class="forgot-testi-stars">
            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          </div>
          <p>"Layanan pelanggan CHRISBALE sangat membantu! Proses reset password cepat dan mudah."</p>
          <div class="forgot-testi-author">— Rina T., Pembeli Terverifikasi</div>
        </div>
      </div>
    </div>

    <!-- RIGHT SIDE -->
    <div class="forgot-right">
      <div class="forgot-form-wrap">

        <div class="forgot-mobile-logo">
          <a href="/" class="logo">CHRIS<span>BALE</span></a>
        </div>

        <div class="forgot-form-header">
          <span class="eyebrow">Bantuan Akun</span>
          <h1>Lupa Password?</h1>
          <p>Masukkan email yang terdaftar dan kami akan kirimkan tautan reset password. <a href="/login" class="forgot-link">Kembali ke Login</a></p>
        </div>

        <!-- Alert Container -->
        <div id="forgotAlert"></div>

        <form class="forgot-form" action="/forgot-password" method="POST">
          @csrf

          <div class="forgot-field">
            <label for="email">Alamat Email</label>
            <div class="forgot-input-wrap">
              <span class="forgot-input-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
              </span>
              <input 
                type="email" 
                id="email" 
                name="email" 
                placeholder="nama@email.com"
                value="{{ old('email') }}"
                autocomplete="email"
                required
              >
            </div>
            @error('email')
              <span class="forgot-field-error">{{ $message }}</span>
            @enderror
          </div>

          <button type="submit" class="forgot-submit-btn">
            <span>Kirim Tautan Reset</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
          </button>
        </form>

        <div class="forgot-form-footer">
          <p><a href="/login" class="forgot-link">Kembali ke halaman Login</a></p>
        </div>

        <div class="forgot-trust">
          <div class="forgot-trust-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            <span>SSL Terenkripsi</span>
          </div>
          <div class="forgot-trust-sep"></div>
          <div class="forgot-trust-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <span>Data Aman</span>
          </div>
          <div class="forgot-trust-sep"></div>
          <div class="forgot-trust-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Terverifikasi</span>
          </div>
        </div>

      </div>
    </div>

  </div>
</div>

@endsection

@push('scripts')
<script>
(function(){
  const inputs = document.querySelectorAll('.forgot-input-wrap input');
  inputs.forEach(function(input){
    input.addEventListener('focus', function(){
      this.closest('.forgot-input-wrap').classList.add('focused');
    });
    input.addEventListener('blur', function(){
      this.closest('.forgot-input-wrap').classList.remove('focused');
    });
  });

  const forgotForm = document.querySelector('.forgot-form');
  if(forgotForm){
    forgotForm.addEventListener('submit', function(e){
      e.preventDefault();

      const submitBtn = forgotForm.querySelector('.forgot-submit-btn');
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML = '<span>Memproses...</span>';
      submitBtn.disabled = true;

      const formData = new FormData(forgotForm);

      fetch('/forgot-password', {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: formData,
      })
      .then(function(res){
        return res.json().then(function(data){ return { status: res.status, data: data }; });
      })
      .then(function(result){
        if(result.data.success){
          showForgotAlert('success', result.data.message);
          forgotForm.querySelector('.forgot-field').style.display = 'none';
          submitBtn.style.display = 'none';
        } else {
          showForgotAlert('error', result.data.message);
        }
      })
      .catch(function(){
        showForgotAlert('error', 'Terjadi kesalahan. Silakan coba lagi.');
      })
      .finally(function(){
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
      });
    });
  }

  function showForgotAlert(type, message){
    var container = document.getElementById('forgotAlert');
    container.innerHTML = '';
    var div = document.createElement('div');
    div.className = 'forgot-alert forgot-alert-' + (type === 'success' ? 'success' : 'error');
    var icon = type === 'success'
      ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>'
      : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>';
    div.innerHTML = icon + '<span>' + message + '</span>';
    container.appendChild(div);
  }
})();
</script>
@endpush
