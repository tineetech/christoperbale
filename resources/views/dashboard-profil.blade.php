@extends('layouts.dashboard')

@section('title', 'Profil & Akun — CHRISBALE')

@php
    $user = Auth::user();
    $userName = $user->full_name ?: $user->nama;
    $userInitials = strtoupper(substr($userName, 0, 1));
@endphp

@push('styles')
    <style>
        /* ---- Flash Alert (sama persis dengan dashboard alamat) ---- */
        .addr-flash {
            position: relative;
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 18px;
            margin-bottom: 22px;
            border-radius: 14px;
            border: 1px solid transparent;
            animation: addrFlashIn 0.35s ease both;
        }

        .addr-flash--success {
            background: linear-gradient(135deg, #ecfdf3 0%, #f6fef9 100%);
            border-color: #bbf7d0;
            box-shadow: 0 6px 20px rgba(22, 163, 74, 0.10);
        }

        .addr-flash--error {
            background: linear-gradient(135deg, #fef2f2 0%, #fffafa 100%);
            border-color: #fecaca;
            box-shadow: 0 6px 20px rgba(220, 38, 38, 0.10);
        }

        .addr-flash-icon {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 24px;
            color: #fff;
        }

        .addr-flash--success .addr-flash-icon {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            box-shadow: 0 0 0 6px rgba(34, 197, 94, 0.15);
        }

        .addr-flash--error .addr-flash-icon {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            box-shadow: 0 0 0 6px rgba(239, 68, 68, 0.15);
        }

        .addr-flash-content { flex: 1; min-width: 0; }

        .addr-flash-title {
            font-size: 14px;
            font-weight: 700;
            letter-spacing: -0.01em;
            margin-bottom: 2px;
        }

        .addr-flash--success .addr-flash-title { color: #166534; }
        .addr-flash--error .addr-flash-title { color: #991b1b; }

        .addr-flash-msg {
            font-size: 13px;
            line-height: 1.5;
            color: #4b5563;
        }

        .addr-flash-close {
            background: none;
            border: none;
            cursor: pointer;
            width: 28px;
            height: 28px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 18px;
            flex-shrink: 0;
            transition: background 0.18s, color 0.18s;
        }

        .addr-flash-close:hover { background: rgba(0, 0, 0, 0.06); color: #374151; }

        @keyframes addrFlashIn {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
@endpush
@section('dashboard-content')
                    <div class="dash-panel" id="panel-profile">
                        <h2 class="dash-section-title" style="margin-bottom:28px;">Profil & Akun</h2>

                        @if (session('success'))
                        <div class="addr-flash addr-flash--success" role="alert" data-flash>
                            <div class="addr-flash-icon">
                                <i class='bx bx-check'></i>
                            </div>
                            <div class="addr-flash-content">
                                <div class="addr-flash-title">Berhasil!</div>
                                <div class="addr-flash-msg">{{ session('success') }}</div>
                            </div>
                            <button type="button" class="addr-flash-close" aria-label="Tutup">
                                <i class='bx bx-x'></i>
                            </button>
                        </div>
                        @endif

                        @if (session('error'))
                        <div class="addr-flash addr-flash--error" role="alert" data-flash>
                            <div class="addr-flash-icon">
                                <i class='bx bx-error'></i>
                            </div>
                            <div class="addr-flash-content">
                                <div class="addr-flash-title">Terjadi kesalahan</div>
                                <div class="addr-flash-msg">{{ session('error') }}</div>
                            </div>
                            <button type="button" class="addr-flash-close" aria-label="Tutup">
                                <i class='bx bx-x'></i>
                            </button>
                        </div>
                        @endif

                        <div class="profile-form-card">
                            <div class="profile-avatar-row">
                                <div class="profile-avatar-big">
                                    @if ($user->photo_profile)
                                    <img src="{{ asset($user->photo_profile) }}" alt="Avatar" referrerpolicy="no-referrer">
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
                                    @php $profilRoleLabel = $user->role->nama_role ?? 'Member'; @endphp
                                    <span class="dash-member-tag" style="margin-top:8px;display:inline-flex;">
                                        <svg width="11" height="11" viewBox="0 0 24 24"
                                            fill="var(--accent-light)" stroke="none">
                                            <polygon
                                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                        </svg>
                                        {{ strtolower($profilRoleLabel) === 'user' ? 'Pengguna' : $profilRoleLabel }}
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
        // Tombol tutup flash alert (sama persis dengan dashboard alamat)
        document.querySelectorAll('[data-flash]').forEach(function (el) {
            el.querySelector('.addr-flash-close').addEventListener('click', function () {
                el.remove();
            });
        });

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