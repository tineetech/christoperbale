@extends('layouts.app')

@section('title', 'Reset Password — CHRISBALE')


@push('styles')
  <style>
    /* =============================================
   RESET PASSWORD PAGE
   ============================================= */

.reset-page {
  min-height: 100vh;
  background: var(--bg);
}

.reset-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  min-height: 100vh;
}

/* =============================================
   LEFT SIDE
   ============================================= */

.reset-left {
  position: relative;
  overflow: hidden;
  background: #0F0D0B;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.reset-left-bg {
  position: absolute;
  inset: 0;
  overflow: hidden;
}

.reset-left-bg img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
  filter: grayscale(30%);
  opacity: 0.45;
  transform: scale(1.05);
  transition: transform 8s ease;
}

.reset-left:hover .reset-left-bg img {
  transform: scale(1);
}

.reset-left-overlay {
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

.reset-left-logo {
  position: relative;
  z-index: 2;
  padding: 36px 44px;
}

.reset-left-logo .logo {
  color: #fff;
  font-size: 22px;
  letter-spacing: 0.1em;
}

.reset-left-logo .logo span {
  color: var(--accent-light);
}

.reset-left-content {
  position: relative;
  z-index: 2;
  padding: 44px;
}

.reset-left-content h2 {
  font-size: clamp(22px, 2.8vw, 34px);
  color: #fff;
  line-height: 1.2;
  margin-bottom: 12px;
  letter-spacing: 0.01em;
  text-shadow: 0 2px 16px rgba(0,0,0,0.5);
}

.reset-left-content h2 em {
  color: var(--accent-light);
  font-style: normal;
}

.reset-left-content > p {
  color: rgba(255, 255, 255, 0.55);
  font-size: 13.5px;
  line-height: 1.75;
  margin-bottom: 28px;
  max-width: 360px;
}

.reset-left-stats {
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

.reset-stat {
  text-align: center;
  flex: 1;
}

.reset-stat-num {
  font-family: 'Playfair Display', serif;
  font-size: 22px;
  font-weight: 700;
  color: #fff;
  line-height: 1;
  margin-bottom: 4px;
}

.reset-stat-label {
  font-size: 10px;
  color: rgba(255, 255, 255, 0.45);
  letter-spacing: 0.1em;
  text-transform: uppercase;
}

.reset-stat-divider {
  width: 1px;
  height: 36px;
  background: rgba(255, 255, 255, 0.12);
  flex-shrink: 0;
}

.reset-left-testi {
  padding: 20px 22px;
  background: rgba(184, 134, 11, 0.12);
  border: 1px solid rgba(184, 134, 11, 0.25);
  border-radius: var(--radius);
  backdrop-filter: blur(6px);
}

.reset-testi-stars {
  display: flex;
  gap: 3px;
  margin-bottom: 10px;
}

.reset-testi-stars svg {
  width: 12px;
  height: 12px;
  fill: var(--accent-light);
  color: var(--accent-light);
}

.reset-left-testi p {
  font-size: 13px;
  color: rgba(255, 255, 255, 0.75);
  line-height: 1.65;
  font-style: italic;
  font-family: 'Playfair Display', serif;
  margin-bottom: 8px;
}

.reset-testi-author {
  font-size: 11px;
  color: rgba(255, 255, 255, 0.4);
  letter-spacing: 0.04em;
}

/* =============================================
   RIGHT SIDE
   ============================================= */

.reset-right {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 48px 24px;
  background: var(--bg);
  min-height: 100vh;
  overflow-y: auto;
}

.reset-form-wrap {
  width: 100%;
  max-width: 440px;
}

.reset-mobile-logo {
  display: none;
  text-align: center;
  margin-bottom: 28px;
}

.reset-mobile-logo .logo {
  font-size: 24px;
  letter-spacing: 0.1em;
  color: var(--ink);
}

.reset-mobile-logo .logo span {
  color: var(--accent);
}

.reset-form-header {
  margin-bottom: 28px;
}

.reset-form-header .eyebrow {
  font-size: 11px;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  color: var(--ink-muted);
  font-weight: 500;
  display: block;
  margin-bottom: 8px;
}

.reset-form-header h1 {
  font-size: clamp(22px, 2.5vw, 30px);
  color: var(--ink);
  margin-bottom: 10px;
  letter-spacing: 0.01em;
}

.reset-form-header p {
  font-size: 13.5px;
  color: var(--ink-soft);
}

.reset-link {
  color: var(--accent);
  font-weight: 500;
  text-decoration: none;
  transition: color 0.2s;
  border-bottom: 1px solid rgba(184, 134, 11, 0.3);
  padding-bottom: 1px;
}

.reset-link:hover {
  color: var(--accent-dark);
  border-bottom-color: var(--accent-dark);
}

/* Alerts */
.reset-alert {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  padding: 12px 16px;
  border-radius: var(--radius-sm);
  font-size: 13px;
  margin-bottom: 20px;
  line-height: 1.5;
}

.reset-alert svg {
  width: 16px;
  height: 16px;
  flex-shrink: 0;
  margin-top: 1px;
}

.reset-alert-error {
  background: rgba(192, 57, 43, 0.08);
  border: 1px solid rgba(192, 57, 43, 0.2);
  color: #C0392B;
}

.reset-alert-error svg {
  stroke: #C0392B;
}

.reset-alert-success {
  background: rgba(39, 174, 96, 0.08);
  border: 1px solid rgba(39, 174, 96, 0.2);
  color: #27AE60;
}

.reset-alert-success svg {
  stroke: #27AE60;
}

/* Form */
.reset-form {
  display: flex;
  flex-direction: column;
  gap: 0;
}

.reset-field {
  margin-bottom: 18px;
}

.reset-field label {
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: 12.5px;
  font-weight: 500;
  color: var(--ink-soft);
  margin-bottom: 8px;
  letter-spacing: 0.03em;
}

.reset-input-wrap {
  position: relative;
  display: flex;
  align-items: center;
  border: 1.5px solid var(--line);
  border-radius: var(--radius-sm);
  background: var(--bg-card);
  transition: border-color 0.2s, box-shadow 0.2s;
  overflow: hidden;
}

.reset-input-wrap.focused {
  border-color: var(--accent);
  box-shadow: 0 0 0 3px rgba(184, 134, 11, 0.1);
}

.reset-input-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 46px;
  height: 100%;
  flex-shrink: 0;
  pointer-events: none;
  padding: 0 4px;
}

.reset-input-icon svg {
  width: 16px;
  height: 16px;
  stroke: var(--ink-muted);
  flex-shrink: 0;
}

.reset-input-wrap.focused .reset-input-icon svg {
  stroke: var(--accent);
}

.reset-input-wrap input {
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

.reset-input-wrap input::placeholder {
  color: var(--ink-muted);
  font-weight: 300;
}

/* Eye Toggle */
.reset-eye-btn {
  width: 44px;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: none;
  border: none;
  cursor: pointer;
  flex-shrink: 0;
  padding: 0 10px;
  color: var(--ink-muted);
  transition: color 0.2s;
}

.reset-eye-btn:hover {
  color: var(--accent);
}

.reset-eye-btn svg {
  width: 16px;
  height: 16px;
}

.reset-field-error {
  display: block;
  font-size: 11.5px;
  color: #C0392B;
  margin-top: 6px;
  letter-spacing: 0.02em;
}

.reset-submit-btn {
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

.reset-submit-btn::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 60%);
}

.reset-submit-btn:hover {
  background: var(--accent-dark);
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(184, 134, 11, 0.3);
}

.reset-submit-btn:active {
  transform: translateY(0);
}

.reset-submit-btn svg {
  width: 14px;
  height: 14px;
  flex-shrink: 0;
}

.reset-form-footer {
  text-align: center;
  padding-top: 8px;
  padding-bottom: 20px;
  border-top: 1px solid var(--line-soft);
  margin-top: 4px;
}

.reset-form-footer p {
  font-size: 11.5px;
  color: var(--ink-muted);
  line-height: 1.65;
}

/* =============================================
   RESPONSIVE
   ============================================= */

@media (max-width: 1024px) {
  .reset-left-content { padding: 36px; }
  .reset-left-logo { padding: 28px 36px; }
  .reset-left-content h2 { font-size: 26px; }
}

@media (max-width: 768px) {
  .reset-grid { grid-template-columns: 1fr; min-height: 100vh; }
  .reset-left { display: none; }
  .reset-right {
    min-height: 100vh;
    padding: 32px 20px;
    align-items: flex-start;
    padding-top: 48px;
  }
  .reset-form-wrap { max-width: 100%; }
  .reset-mobile-logo { display: block; }
  .reset-form-header { text-align: center; }
  .reset-form-header p { text-align: center; }
  .reset-submit-btn { padding: 16px 24px; }
}

@media (max-width: 400px) {
  .reset-right { padding: 32px 16px; padding-top: 40px; }
  .reset-form-header h1 { font-size: 22px; }
}

@media (max-width: 768px) and (orientation: landscape) {
  .reset-right { padding: 24px 20px; align-items: center; }
}

@media (min-width: 1440px) {
  .reset-left-content { padding: 52px; }
  .reset-left-logo { padding: 40px 52px; }
  .reset-form-wrap { max-width: 480px; }
}
  </style>
@endpush

@section('content')

<div class="reset-page">
  <div class="reset-grid">

    <!-- LEFT SIDE -->
    <div class="reset-left">
      <div class="reset-left-bg">
        <img 
          src="https://images.unsplash.com/photo-1553729789-5c8c6e214e9f?w=900&q=80&auto=format&fit=crop" 
          alt="CHRISBALE Premium Footwear"
        >
      </div>
      <div class="reset-left-overlay"></div>

      <div class="reset-left-logo">
        <a href="/" class="logo">CHRIS<span>BALE</span></a>
      </div>

      <div class="reset-left-content">
        <h2>Buat Password <em>Baru</em> Anda</h2>
        <p>Masukkan password baru untuk akun CHRISBALE Anda. Pastikan kuat dan mudah diingat.</p>

        <div class="reset-left-stats">
          <div class="reset-stat">
            <div class="reset-stat-num">50K+</div>
            <div class="reset-stat-label">Pelanggan</div>
          </div>
          <div class="reset-stat-divider"></div>
          <div class="reset-stat">
            <div class="reset-stat-num">500+</div>
            <div class="reset-stat-label">Koleksi</div>
          </div>
          <div class="reset-stat-divider"></div>
          <div class="reset-stat">
            <div class="reset-stat-num">4.9</div>
            <div class="reset-stat-label">Rating</div>
          </div>
        </div>

        <div class="reset-left-testi">
          <div class="reset-testi-stars">
            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          </div>
          <p>"CHRISBALE selalu memberikan pengalaman terbaik, termasuk proses reset password yang cepat!"</p>
          <div class="reset-testi-author">— Dian S., Pembeli Terverifikasi</div>
        </div>
      </div>
    </div>

    <!-- RIGHT SIDE -->
    <div class="reset-right">
      <div class="reset-form-wrap">

        <div class="reset-mobile-logo">
          <a href="/" class="logo">CHRIS<span>BALE</span></a>
        </div>

        <div class="reset-form-header">
          <span class="eyebrow">Reset Password</span>
          <h1>Buat Password Baru</h1>
          <p>Masukkan password baru untuk akun <strong>{{ $email }}</strong></p>
        </div>

        <!-- Alert Container -->
        <div id="resetAlert"></div>

        <form class="reset-form" action="/reset-password" method="POST">
          @csrf
          <input type="hidden" name="token" value="{{ $token }}">
          <input type="hidden" name="email" value="{{ $email }}">

          <!-- Password Baru -->
          <div class="reset-field">
            <label for="password">Password Baru</label>
            <div class="reset-input-wrap">
              <span class="reset-input-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
              </span>
              <input 
                type="password" 
                id="password" 
                name="password" 
                placeholder="Minimal 6 karakter"
                autocomplete="new-password"
                required
              >
              <button type="button" class="reset-eye-btn" id="togglePassword" aria-label="Tampilkan password">
                <svg id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                <svg id="eyeOffIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="display:none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
              </button>
            </div>
          </div>

          <!-- Konfirmasi Password -->
          <div class="reset-field">
            <label for="password_confirmation">Konfirmasi Password</label>
            <div class="reset-input-wrap">
              <span class="reset-input-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
              </span>
              <input 
                type="password" 
                id="password_confirmation" 
                name="password_confirmation" 
                placeholder="Ulangi password"
                autocomplete="new-password"
                required
              >
            </div>
          </div>

          <button type="submit" class="reset-submit-btn">
            <span>Reset Password</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </form>

        <div class="reset-form-footer">
          <p><a href="/login" class="reset-link">Kembali ke halaman Login</a></p>
        </div>

      </div>
    </div>

  </div>
</div>

@endsection

@push('scripts')
<script>
(function(){
  // Toggle Password
  const toggleBtn = document.getElementById('togglePassword');
  const passwordInput = document.getElementById('password');
  const eyeIcon = document.getElementById('eyeIcon');
  const eyeOffIcon = document.getElementById('eyeOffIcon');

  if(toggleBtn) {
    toggleBtn.addEventListener('click', function(){
      const isPassword = passwordInput.type === 'password';
      passwordInput.type = isPassword ? 'text' : 'password';
      eyeIcon.style.display = isPassword ? 'none' : 'block';
      eyeOffIcon.style.display = isPassword ? 'block' : 'none';
    });
  }

  // Input focus
  const inputs = document.querySelectorAll('.reset-input-wrap input');
  inputs.forEach(function(input){
    input.addEventListener('focus', function(){
      this.closest('.reset-input-wrap').classList.add('focused');
    });
    input.addEventListener('blur', function(){
      this.closest('.reset-input-wrap').classList.remove('focused');
    });
  });

  // Submit via AJAX
  const resetForm = document.querySelector('.reset-form');
  if(resetForm){
    resetForm.addEventListener('submit', function(e){
      e.preventDefault();

      const submitBtn = resetForm.querySelector('.reset-submit-btn');
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML = '<span>Memproses...</span>';
      submitBtn.disabled = true;

      const formData = new FormData(resetForm);

      fetch('/reset-password', {
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
          showResetAlert('success', result.data.message);
          resetForm.querySelectorAll('.reset-field').forEach(function(f){ f.style.display = 'none'; });
          submitBtn.style.display = 'none';
          setTimeout(function(){ window.location.href = '/login'; }, 2000);
        } else {
          showResetAlert('error', result.data.message);
        }
      })
      .catch(function(){
        showResetAlert('error', 'Terjadi kesalahan. Silakan coba lagi.');
      })
      .finally(function(){
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
      });
    });
  }

  function showResetAlert(type, message){
    var container = document.getElementById('resetAlert');
    container.innerHTML = '';
    var div = document.createElement('div');
    div.className = 'reset-alert reset-alert-' + (type === 'success' ? 'success' : 'error');
    var icon = type === 'success'
      ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>'
      : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>';
    div.innerHTML = icon + '<span>' + message + '</span>';
    container.appendChild(div);
  }
})();
</script>
@endpush
