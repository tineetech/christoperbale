@extends('layouts.dashboard')

@section('title', 'Profil & Akun — CHRISBALE')

@php
    $user = Auth::user();
    $userName = $user->full_name ?: $user->nama;
    $userInitials = strtoupper(substr($userName, 0, 1));
@endphp
@section('dashboard-content')
                    <div class="dash-panel" id="panel-profile">
                        <h2 class="dash-section-title" style="margin-bottom:28px;">Profil & Akun</h2>

                        @if (session('success'))
                        <div class="dash-alert" style="border-color:var(--green);background:rgba(46,125,50,0.06);margin-bottom:20px;">
                            <div class="dash-alert-dot" style="background:var(--green);animation:none;"></div>
                            <div class="dash-alert-content">
                                <strong style="color:var(--green);">{{ session('success') }}</strong>
                            </div>
                        </div>
                        @endif

                        <div class="profile-form-card">
                            <div class="profile-avatar-row">
                                <div class="profile-avatar-big">
                                    @if ($user->photo_profile)
                                    <img src="{{ asset($user->photo_profile) }}" alt="Avatar">
                                    @else
                                    <div class="profile-avatar-initials">{{ $userInitials }}</div>
                                    @endif
                                    <label class="profile-avatar-change" for="photo_input" style="cursor:pointer;">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                                        </svg>
                                    </label>
                                </div>
                                <div>
                                    <h3 style="font-size:18px;margin-bottom:4px;">{{ $userName }}</h3>
                                    <p style="color:var(--ink-muted);font-size:13px;margin:0;">{{ $user->email }}</p>
                                    @if ($user->role)
                                    <span class="dash-member-tag" style="margin-top:8px;display:inline-flex;">
                                        <svg width="11" height="11" viewBox="0 0 24 24"
                                            fill="var(--accent-light)" stroke="none">
                                            <polygon
                                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                        </svg>
                                        {{ $user->role->nama_role ?? 'Member' }}
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <form method="POST" action="{{ route('dashboard.profil.update') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="file" id="photo_input" name="photo_profile" accept="image/*" style="display:none;" onchange="this.form.submit()">
                                <div class="pform-grid">
                                    <div class="form-group">
                                        <label for="nama">Nama Pengguna</label>
                                        <input type="text" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required>
                                        @error('nama') <span style="color:var(--red);font-size:12px;">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="full_name">Nama Lengkap</label>
                                        <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $user->full_name) }}">
                                        @error('full_name') <span style="color:var(--red);font-size:12px;">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Alamat Email</label>
                                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                        @error('email') <span style="color:var(--red);font-size:12px;">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Nomor Telepon</label>
                                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                        @error('phone') <span style="color:var(--red);font-size:12px;">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="gender">Jenis Kelamin</label>
                                        <select id="gender" name="gender">
                                            <option value="">Pilih</option>
                                            <option value="Laki-laki" {{ (old('gender', $user->gender) == 'Laki-laki') ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="Perempuan" {{ (old('gender', $user->gender) == 'Perempuan') ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        @error('gender') <span style="color:var(--red);font-size:12px;">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:8px;">
                                    <button type="submit" class="btn-submit">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>

                        <!-- Ubah Kata Sandi -->
                        <div class="profile-form-card" style="margin-top:24px;">
                            <div style="display:flex;align-items:center;justify-content:space-between;cursor:pointer;" onclick="togglePasswordForm()">
                                <h3 style="font-size:15px;font-family:'Inter',sans-serif;font-weight:600;margin:0;">
                                    Ubah Kata Sandi
                                </h3>
                                <svg id="passwordToggleIcon" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    style="transition:transform 0.25s;color:var(--ink-muted);">
                                    <polyline points="6 9 12 15 18 9" />
                                </svg>
                            </div>

                            <div id="passwordFormWrap" style="display:none;margin-top:20px;">
                                <form method="POST" action="{{ route('dashboard.profil.password') }}">
                                    @csrf
                                    <div class="pform-grid">
                                        <div class="form-group">
                                            <label for="current_password">Kata Sandi Saat Ini</label>
                                            <input type="password" id="current_password" name="current_password" placeholder="••••••••" required>
                                            @error('current_password') <span style="color:var(--red);font-size:12px;">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Kata Sandi Baru</label>
                                            <input type="password" id="password" name="password" placeholder="••••••••" required>
                                            @error('password') <span style="color:var(--red);font-size:12px;">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="password_confirmation">Konfirmasi Kata Sandi Baru</label>
                                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
                                        </div>
                                    </div>
                                    <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:8px;">
                                        <button type="submit" class="btn-submit">Simpan Kata Sandi</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div><!-- /panel-profile -->
@endsection

@push('scripts')
    <script>
        function togglePasswordForm() {
            var wrap = document.getElementById('passwordFormWrap');
            var icon = document.getElementById('passwordToggleIcon');
            if (wrap.style.display === 'none' || wrap.style.display === '') {
                wrap.style.display = 'block';
                wrap.style.animation = 'fadeInUp 0.3s ease';
                icon.style.transform = 'rotate(180deg)';
            } else {
                wrap.style.display = 'none';
                icon.style.transform = 'rotate(0deg)';
            }
        }
    </script>
@endpush