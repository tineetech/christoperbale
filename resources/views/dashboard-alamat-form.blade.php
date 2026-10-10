@extends('layouts.dashboard')

@section('title', ($address ? 'Edit' : 'Tambah') . ' Alamat — CHRISBALE')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <style>
        .select2-container {
            width: 100% !important;
            font-family: 'Inter', sans-serif;
        }
        .select2-container .select2-selection--single {
            height: auto;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            background: #fafafa;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            font-size: 13.5px;
            color: #111827;
            padding: 9px 12px;
            line-height: 1.4;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
        }
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: var(--accent);
            background: #fff;
        }
        .select2-dropdown {
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }
        .select2-search--dropdown .select2-search__field {
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            padding: 8px 10px;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
        }
        .select2-results__option {
            font-size: 13px;
            padding: 8px 12px;
        }
    </style>
    <style>
        .addr-form-wrap {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            overflow: hidden;
            max-width: 1080px;
        }

        .addr-form-header {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 28px 32px;
            border-bottom: 1px solid #f3f4f6;
            background: linear-gradient(135deg, #fafafa 0%, #ffffff 100%);
        }

        .addr-form-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: rgba(184, 134, 11, 0.10);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .addr-form-icon svg {
            width: 22px;
            height: 22px;
            stroke: var(--accent);
            fill: none;
            stroke-width: 2;
        }

        .addr-form-title {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
            letter-spacing: -0.02em;
            margin: 0;
        }

        .addr-form-subtitle {
            font-size: 13px;
            color: #9ca3af;
            margin: 4px 0 0;
        }

        .addr-form-body {
            padding: 32px;
        }

        .addr-form-section-title {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #9ca3af;
            margin: 0 0 4px;
        }

        .addr-form-section-desc {
            font-size: 11px;
            color: #9ca3af;
            margin: 0 0 16px;
            line-height: 1.5;
        }

        .addr-form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .addr-form-grid .form-group--full {
            grid-column: 1 / -1;
        }

        .addr-form-cols {
            display: grid;
            grid-template-columns: 3fr 2fr;
            gap: 28px;
            align-items: start;
        }

        .addr-form-cols .form-group {
            margin-bottom: 18px;
        }

        .addr-form-cols .form-group:last-child {
            margin-bottom: 0;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 7px;
            letter-spacing: 0.01em;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 13.5px;
            font-family: 'Inter', sans-serif;
            color: #111827;
            background: #fafafa;
            transition: border-color .15s, background .15s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--accent);
            background: #fff;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .addr-map-card {
            border: 1.5px solid #e5e7eb;
            border-radius: 14px;
            overflow: hidden;
            background: #f9fafb;
        }

        .addr-map-card-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border-bottom: 1px solid #f3f4f6;
        }

        .addr-map-card-header svg {
            width: 16px;
            height: 16px;
            stroke: var(--accent);
            fill: none;
            stroke-width: 2;
        }

        .addr-map-card-header span {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        #addrMapForm {
            height: 220px;
            width: 100%;
            z-index: 0;
        }

        .addr-map-hint {
            font-size: 11.5px;
            color: #6b7280;
            padding: 10px 16px;
        }

        .addr-checkbox-modern {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 12px 16px;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            background: #fafafa;
            transition: border-color .15s, background .15s;
            width: 100%;
        }

        .addr-checkbox-modern:hover {
            border-color: var(--accent);
            background: rgba(184, 134, 11, 0.03);
        }

        .addr-checkbox-modern input[type="checkbox"] {
            accent-color: var(--accent);
            width: 16px;
            height: 16px;
        }

        .addr-checkbox-modern-text {
            display: flex;
            flex-direction: column;
            gap: 1px;
        }

        .addr-checkbox-modern-text strong {
            font-size: 13px;
            font-weight: 600;
            color: #111827;
        }

        .addr-checkbox-modern-text span {
            font-size: 11.5px;
            color: #9ca3af;
        }

        .addr-form-divider {
            height: 1px;
            background: #f3f4f6;
            margin: 28px 0;
        }

        .addr-form-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .addr-btn-back {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            font-weight: 500;
            color: #6b7280;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            padding: 11px 20px;
            background: #fff;
            text-decoration: none;
            transition: all .18s;
        }

        .addr-btn-back:hover {
            border-color: #9ca3af;
            color: #374151;
        }

        .addr-btn-save {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 12px 28px;
            background: var(--accent);
            cursor: pointer;
            transition: background .2s;
            letter-spacing: 0.02em;
        }

        .addr-btn-save:hover {
            background: var(--accent-dark);
        }

        .addr-error-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 13px;
            margin-bottom: 24px;
        }

        @media (max-width: 860px) {
            .addr-form-cols {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            .addr-form-grid {
                grid-template-columns: 1fr;
            }
            .addr-form-body {
                padding: 20px;
            }
            .addr-form-header {
                padding: 20px;
            }
        }
    </style>
@endpush

@section('dashboard-content')
    <div class="dash-panel">
        {{-- Breadcrumb --}}
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:24px;font-size:13px;color:#9ca3af;">
            <a href="{{ route('dashboard.alamat') }}" style="color:#9ca3af;text-decoration:none;display:inline-flex;align-items:center;gap:5px;">
                <i class='bx bx-map-pin' style="font-size:16px;"></i>
                Alamat
            </a>
            <span>›</span>
            <span style="color:#374151;">{{ $address ? 'Edit Alamat' : 'Tambah Alamat' }}</span>
        </div>

        <div class="addr-form-wrap">
            <div class="addr-form-header">
                <div class="addr-form-icon">
                    @if ($address)
                        <i class='bx bx-edit-alt' style="font-size:24px;color:var(--accent);"></i>
                    @else
                        <i class='bx bx-map-pin' style="font-size:24px;color:var(--accent);"></i>
                    @endif
                </div>
                <div>
                    <h2 class="addr-form-title">{{ $address ? 'Edit Alamat' : 'Tambah Alamat Baru' }}</h2>
                    <p class="addr-form-subtitle">{{ $address ? 'Perbarui informasi alamat pengiriman.' : 'Isi detail alamat pengiriman baru Anda.' }}</p>
                </div>
            </div>

            <div class="addr-form-body">
                @if ($errors->any())
                    <div class="addr-error-box">
                        <strong>Periksa kembali isian form:</strong>
                        <ul style="margin:6px 0 0 16px;padding:0;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST"
                    action="{{ $address ? route('dashboard.alamat.update', $address->id) : route('dashboard.alamat.store') }}"
                    id="addrForm">
                    @csrf
                    @if ($address)
                        @method('PUT')
                    @endif

                    <div class="addr-form-cols">
                        <div>
                            {{-- Identitas --}}
                            <p class="addr-form-section-title">Identitas</p>
                            <div class="addr-form-grid">
                                <div class="form-group">
                                    <label>Label Alamat <span style="color:var(--red);">*</span></label>
                                    <input type="text" name="label" value="{{ old('label', $address->label ?? '') }}" placeholder="Contoh: Rumah, Kantor" required>
                                </div>
                                <div class="form-group">
                                    <label>Nama Penerima <span style="color:var(--red);">*</span></label>
                                    <input type="text" name="receiver_name" value="{{ old('receiver_name', $address->receiver_name ?? '') }}" placeholder="Nama lengkap" required>
                                </div>
                                <div class="form-group form-group--full">
                                    <label>Nomor Telepon <span style="color:var(--red);">*</span></label>
                                    <input type="tel" name="phone" value="{{ old('phone', $address->phone ?? '') }}" placeholder="08xx-xxxx-xxxx" required>
                                </div>
                            </div>

                            <div class="addr-form-divider"></div>

                            {{-- Wilayah --}}
                            <p class="addr-form-section-title">Wilayah</p>
                            <p class="addr-form-section-desc">Pilih provinsi, lalu kota, kecamatan, dan kode pos sesuai alamat Anda.</p>
                            <div class="addr-form-grid">
                        <div class="form-group">
                            <label>Provinsi <span style="color:var(--red);">*</span></label>
                            @php
                                $provList = ['Aceh','Bali','Banten','Bengkulu','DI Yogyakarta','DKI Jakarta','Gorontalo','Jambi','Jawa Barat','Jawa Tengah','Jawa Timur','Kalimantan Barat','Kalimantan Selatan','Kalimantan Tengah','Kalimantan Timur','Kalimantan Utara','Kepulauan Bangka Belitung','Kepulauan Riau','Lampung','Maluku','Maluku Utara','Nusa Tenggara Barat','Nusa Tenggara Timur','Papua','Papua Barat','Papua Barat Daya','Papua Pegunungan','Papua Selatan','Papua Tengah','Riau','Sulawesi Barat','Sulawesi Selatan','Sulawesi Tengah','Sulawesi Tenggara','Sulawesi Utara','Sumatera Barat','Sumatera Selatan','Sumatera Utara'];
                                $savedProv = old('province', $address->province ?? '');
                            @endphp
                            <select name="province" id="addr_province" required>
                                <option value="">Pilih Provinsi</option>
                                @foreach ($provList as $prov)
                                    <option value="{{ $prov }}" {{ $savedProv == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                                @endforeach
                                @if($savedProv && !in_array($savedProv, $provList))
                                    <option value="{{ $savedProv }}" selected>{{ $savedProv }}</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Kota/Kabupaten <span style="color:var(--red);">*</span></label>
                            <select name="city" id="addr_city" required>
                                <option value="">Pilih/ketik kota</option>
                                @if(old('city', $address->city ?? ''))
                                    <option value="{{ old('city', $address->city ?? '') }}" selected>{{ old('city', $address->city ?? '') }}</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Kecamatan <span style="color:var(--red);">*</span></label>
                            <select name="district" id="addr_district" required>
                                <option value="">Pilih/ketik kecamatan</option>
                                @if(old('district', $address->district ?? ''))
                                    <option value="{{ old('district', $address->district ?? '') }}" selected>{{ old('district', $address->district ?? '') }}</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Kode Pos <span style="color:var(--red);">*</span></label>
                            <input type="text" name="postal_code" id="addr_postal_code" value="{{ old('postal_code', $address->postal_code ?? '') }}" placeholder="Contoh: 12190" required>
                        </div>
                            </div>
                        </div>

                        <div>
                            {{-- Alamat & Titik Lokasi --}}
                            <p class="addr-form-section-title">Alamat & Titik Lokasi</p>
                            <div class="form-group">
                                <label>Alamat Lengkap <span style="color:var(--red);">*</span></label>
                                <textarea name="address" id="addr_address" placeholder="Jl. Nama Jalan No.xx, RT/RW, Kelurahan, dsb." required>{{ old('address', $address->address ?? '') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Catatan <span style="color:#9ca3af;font-weight:400;">(opsional)</span></label>
                                <textarea name="catatan" placeholder="Contoh: dekat warung Bu Indah, pagar hijau..." style="min-height:60px;">{{ old('catatan', $address->catatan ?? '') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Titik Lokasi <span style="color:var(--red);">*</span></label>
                                <div class="addr-map-card">
                                    <div class="addr-map-card-header">
                                        <i class='bx bx-map-pin' style="font-size:18px;color:var(--accent);"></i>
                                        <span>Klik peta atau geser marker — samakan dengan alamat yang diketik</span>
                                    </div>
                                    <div id="addrMapForm"></div>
                                    <div class="addr-map-hint" id="addrMapHint">Pastikan alamat dan titik lokasi di peta sesuai agar pesanan sampai dengan tepat dan cepat. Periksa kembali sebelum menyimpan alamat!</div>
                                </div>
                            </div>

                            <input type="hidden" name="area_id" id="addr_area_id" value="{{ old('area_id', $address->area_id ?? '') }}">
                            <input type="hidden" name="latitude" id="addr_lat" value="{{ old('latitude', $address->latitude ?? '') }}">
                            <input type="hidden" name="longitude" id="addr_lng" value="{{ old('longitude', $address->longitude ?? '') }}">

                            {{-- Is Default --}}
                            <label class="addr-checkbox-modern" style="margin-bottom:0;">
                                <input type="checkbox" name="is_default" value="1"
                                    {{ old('is_default', $address?->is_default ?? false) ? 'checked' : '' }}>
                                <div class="addr-checkbox-modern-text">
                                    <strong>Jadikan alamat utama</strong>
                                    <span>Alamat ini akan digunakan sebagai default saat checkout</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="addr-form-divider"></div>

                    <div class="addr-form-footer">
                        <a href="{{ route('dashboard.alamat') }}" class="addr-btn-back">
                            <i class='bx bx-arrow-back' style="font-size:16px;"></i>
                            Kembali
                        </a>
                        <button type="submit" class="addr-btn-save" id="addrSubmitBtn">
                            <i class='bx bx-check' style="font-size:18px;"></i>
                            {{ $address ? 'Simpan Perubahan' : 'Simpan Alamat' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    var addrMap = null, addrMarker = null;
    var csrfToken = document.querySelector('meta[name=csrf-token]') ? document.querySelector('meta[name=csrf-token]').content : '';
    var areaUrl = '{{ route("dashboard.alamat.area") }}';

    var initLat = {{ ($address?->latitude) ? (float) $address->latitude : -6.5950 }};
    var initLng = {{ ($address?->longitude) ? (float) $address->longitude : 106.8166 }};
    var hasInitPin = {{ ($address?->latitude && $address?->longitude) ? 'true' : 'false' }};

    // Init map
    addrMap = L.map('addrMapForm', { scrollWheelZoom: false }).setView([initLat, initLng], hasInitPin ? 16 : 13);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(addrMap);

    addrMap.on('click', function(e) {
        // Pin hanya mengisi koordinat. TIDAK fetch/update Biteship di sini.
        addrSetMarker(e.latlng.lat, e.latlng.lng);
    });

    // If editing and has coords, set initial marker
    if (hasInitPin) {
        addrSetMarker(initLat, initLng);
    }

    function addrSetMarker(lat, lng) {
        if (addrMarker) {
            addrMarker.setLatLng([lat, lng]);
        } else {
            addrMarker = L.marker([lat, lng], { draggable: true }).addTo(addrMap);
            addrMarker.on('dragend', function() {
                var p = addrMarker.getLatLng();
                addrSetMarker(p.lat, p.lng);
            });
        }
        addrMap.setView([lat, lng], 16);
        document.getElementById('addr_lat').value = lat;
        document.getElementById('addr_lng').value = lng;
        document.getElementById('addrMapHint').textContent = 'Pastikan titik posisi kordinat sama persis dengan alamat yang Anda ketik di form. Supaya pengiriman bisa Tepat dan Cepat.';
    }

    // Cari area_id Biteship dari alamat yang diketik user
    // (nama jalan/provinsi/kota/kecamatan/kode pos), seperti perilaku awal.
    function addrResolveArea() {
        var district = document.getElementById('addr_district').value;
        var city = document.getElementById('addr_city').value;
        var province = document.getElementById('addr_province').value;
        var postal = document.getElementById('addr_postal_code').value;
        if (!city && !district && !province) return;
        fetch(areaUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ district: district, city: city, province: province, postal_code: postal })
        }).then(function(r) { return r.json(); }).then(function(res) {
            document.getElementById('addr_area_id').value = res.area_id || '';
        }).catch(function() {
            document.getElementById('addr_area_id').value = '';
        });
    }

    function addrResetAndResolveArea() {
        document.getElementById('addr_area_id').value = '';
        addrResolveArea();
    }

    var addrResolveTimer = null;
    function addrResetAndResolveAreaDebounced() {
        document.getElementById('addr_area_id').value = '';
        if (addrResolveTimer) clearTimeout(addrResolveTimer);
        addrResolveTimer = setTimeout(addrResolveArea, 600);
    }

    // Text inputs: perubahan HANYA me-reset area_id + resolve ulang.
    // TIDAK mengubah nilai field lain.
    ['addr_postal_code', 'addr_address'].forEach(function(fid) {
        var el = document.getElementById(fid);
        if (!el) return;
        var onEdit = function() {
            document.getElementById('addr_area_id').value = '';
            addrResetAndResolveAreaDebounced();
        };
        if (window.jQuery) {
            window.jQuery(el).on('change input', onEdit);
        } else {
            el.addEventListener('change', onEdit);
            el.addEventListener('input', onEdit);
        }
    });

    // =============================================
    // Wilayah bertahap via proxy wilayah.id (NON-DESTRUKTIF):
    // - Opsi select HANYA ditambah (append), tidak pernah dihapus/direset.
    // - Nilai pilihan user tidak pernah ditimpa/diubah oleh kode.
    // - Fetch hanya saat user berinteraksi (buka dropdown / ganti pilihan).
    // =============================================
    var provinceCodeByName = {};
    var cityCodeByName = {};
    var citiesLoadedFor = '';
    var districtsLoadedFor = '';
    var wilayahProvincesUrl = '{{ route("dashboard.wilayah.provinces") }}';
    var wilayahRegenciesBaseUrl = '{{ url("/dashboard/wilayah/regencies") }}';
    var wilayahDistrictsBaseUrl = '{{ url("/dashboard/wilayah/districts") }}';

    function hasSelect2() {
        return (typeof window.jQuery !== 'undefined') && !!window.jQuery.fn.select2;
    }

    function sortAbjad(a, b) {
        return String(a).localeCompare(String(b), 'id');
    }

    // Refresh dropdown yang sedang terbuka agar opsi hasil fetch langsung tampil.
    function refreshOpenDropdown(selId) {
        if (!hasSelect2()) return;
        var $s = window.jQuery('#' + selId);
        var inst = $s.data('select2');
        if (inst && inst.isOpen()) {
            $s.select2('close');
            $s.select2('open');
        }
    }

    // Tambah opsi tanpa menghapus yang sudah ada (termasuk ketikan user).
    function appendOptions(selId, names) {
        var sel = document.getElementById(selId);
        var existing = {};
        for (var i = 0; i < sel.options.length; i++) {
            existing[sel.options[i].value] = true;
        }
        names.slice().sort(sortAbjad).forEach(function(name) {
            if (!existing[name]) {
                var opt = document.createElement('option');
                opt.value = name;
                opt.textContent = name;
                sel.appendChild(opt);
            }
        });
        refreshOpenDropdown(selId);
    }

    function initRegionSelect2() {
        if (!hasSelect2() || initRegionSelect2.done) return;
        initRegionSelect2.done = true;
        var tagLang = {
            noResults: function() { return 'Tidak ditemukan — tekan Enter untuk memakai ketikan Anda'; },
            searching: function() { return 'Mencari...'; }
        };
        window.jQuery('#addr_province').select2({ placeholder: 'Pilih Provinsi', width: '100%' });
        window.jQuery('#addr_city').select2({ placeholder: 'Pilih/ketik kota', tags: true, width: '100%', language: tagLang });
        window.jQuery('#addr_district').select2({ placeholder: 'Pilih/ketik kecamatan', tags: true, width: '100%', language: tagLang });
    }

    // Bangun peta nama provinsi -> kode (untuk fetch kota).
    // Tidak menyentuh opsi/nilai select sama sekali.
    function ensureProvinceCodes(cb) {
        if (Object.keys(provinceCodeByName).length) { if (cb) cb(); return; }
        fetch(wilayahProvincesUrl)
            .then(function(r) { return r.json(); })
            .then(function(res) {
                ((res && res.data) || []).forEach(function(row) {
                    provinceCodeByName[row.name] = row.code;
                });
                // Alias nama lama -> kode resmi.
                if (provinceCodeByName['Daerah Istimewa Yogyakarta'] && !provinceCodeByName['DI Yogyakarta']) {
                    provinceCodeByName['DI Yogyakarta'] = provinceCodeByName['Daerah Istimewa Yogyakarta'];
                }
                if (cb) cb();
            })
            .catch(function() { if (cb) cb(); });
    }

    function ensureCities() {
        var prov = document.getElementById('addr_province').value;
        if (!prov || citiesLoadedFor === prov) return;
        ensureProvinceCodes(function() {
            var code = provinceCodeByName[prov];
            if (!code) return;
            fetch(wilayahRegenciesBaseUrl + '/' + encodeURIComponent(code))
                .then(function(r) { return r.json(); })
                .then(function(res) {
                    var rows = (res && res.data) || [];
                    cityCodeByName = {};
                    var names = rows.map(function(row) {
                        cityCodeByName[row.name] = row.code;
                        return row.name;
                    });
                    appendOptions('addr_city', names);
                    citiesLoadedFor = prov;
                })
                .catch(function() {});
        });
    }

    function ensureDistricts() {
        var city = document.getElementById('addr_city').value;
        if (!city || districtsLoadedFor === city) return;
        var code = cityCodeByName[city];
        if (!code) return;
        fetch(wilayahDistrictsBaseUrl + '/' + encodeURIComponent(code))
            .then(function(r) { return r.json(); })
            .then(function(res) {
                var rows = (res && res.data) || [];
                appendOptions('addr_district', rows.map(function(row) { return row.name; }));
                districtsLoadedFor = city;
            })
            .catch(function() {});
    }

    // Flag agar sinkronisasi tampilan Select2 tidak memicu handler change (anti rekursi).
    var suppressRegionChange = false;

    // Set value select + sinkronkan tampilan Select2 TANPA menjalankan handler.
    function setSelectValueQuiet(selId, value) {
        var sel = document.getElementById(selId);
        if (!sel) return;
        sel.value = value;
        if (hasSelect2()) {
            suppressRegionChange = true;
            try {
                window.jQuery('#' + selId).trigger('change');
            } finally {
                suppressRegionChange = false;
            }
        }
    }

    // Aturan reset (hanya saat value DIUBAH user, bukan saat diklik/dibuka):
    // - Provinsi diubah  -> kota, kecamatan, kode pos dihapus nilainya.
    // - Kota diubah      -> provinsi stay, kecamatan + kode pos dihapus.
    // - Kecamatan diubah -> provinsi + kota stay, kode pos dihapus.
    // - Kode pos diubah  -> semua induk stay.
    function handleProvinceChange(value) {
        if (suppressRegionChange) return;
        var hasProvince = !!value;
        document.getElementById('addr_area_id').value = '';
        // Reset cache agar fetch berikutnya tidak memakai data provinsi lama.
        citiesLoadedFor = '';
        districtsLoadedFor = '';
        cityCodeByName = {};
        if (hasProvince) {
            setSelectValueQuiet('addr_city', '');
            setSelectValueQuiet('addr_district', '');
            document.getElementById('addr_postal_code').value = '';
            ensureCities();
        } else {
            setSelectValueQuiet('addr_city', '');
            setSelectValueQuiet('addr_district', '');
            document.getElementById('addr_postal_code').value = '';
        }
        addrResolveArea();
    }

    function handleCityChange(value) {
        if (suppressRegionChange) return;
        var hasCity = !!value;
        document.getElementById('addr_area_id').value = '';
        // Reset cache kecamatan agar fetch berikutnya tidak memakai data kota lama.
        districtsLoadedFor = '';
        if (hasCity) {
            setSelectValueQuiet('addr_district', '');
            document.getElementById('addr_postal_code').value = '';
            ensureDistricts();
        } else {
            setSelectValueQuiet('addr_district', '');
            document.getElementById('addr_postal_code').value = '';
        }
        addrResolveArea();
    }

    function handleDistrictChange() {
        if (suppressRegionChange) return;
        document.getElementById('addr_area_id').value = '';
        document.getElementById('addr_postal_code').value = '';
        addrResolveArea();
    }

    function bindRegionSelect(fid, handler) {
        var el = document.getElementById(fid);
        if (!el) return;
        if (window.jQuery) {
            // Menangkap change native maupun trigger Select2.
            window.jQuery(el).on('change', handler);
        } else {
            el.addEventListener('change', handler);
        }
    }

    bindRegionSelect('addr_province', function() { handleProvinceChange(this.value); });
    bindRegionSelect('addr_city', function() { handleCityChange(this.value); });
    bindRegionSelect('addr_district', function() { handleDistrictChange(this.value); });

    if (hasSelect2()) {
        window.jQuery('#addr_city').on('select2:open', function() { ensureCities(); });
        window.jQuery('#addr_district').on('select2:open', function() { ensureDistricts(); });
    } else {
        document.getElementById('addr_city').addEventListener('focus', function() { ensureCities(); });
        document.getElementById('addr_district').addEventListener('focus', function() { ensureDistricts(); });
    }

    initRegionSelect2();


    // Require map pin before submit
    document.getElementById('addrForm').addEventListener('submit', function(e) {
        var lat = document.getElementById('addr_lat').value;
        var lng = document.getElementById('addr_lng').value;
        if (!lat || !lng) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Pilih Titik Lokasi',
                text: 'Silakan klik peta atau geser marker untuk menentukan titik lokasi sebelum menyimpan.'
            });
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
