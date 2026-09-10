@extends('layouts.dashboard')

@section('title', 'Alamat Pengiriman — CHRISBALE')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <style>
        .addr-geo-hint {
            font-size: 11px;
            color: var(--ink-muted);
            margin-top: 6px;
        }

        .addr-map-wrap {
            border: 1px solid var(--line);
            border-radius: var(--radius-sm);
            overflow: hidden;
        }

        .addr-map {
            height: 260px;
            width: 100%;
            z-index: 0;
        }
    </style>
@endpush

@section('dashboard-content')
                    <div class="dash-panel" id="panel-address">
                        <h2 class="dash-section-title " style="margin-bottom:28px;">Alamat Pengiriman</h2>

                        @if (session('success'))
                        <div class="dash-alert" style="border-color:var(--green);background:rgba(46,125,50,0.06);margin-bottom:20px;">
                            <div class="dash-alert-dot" style="background:var(--green);animation:none;"></div>
                            <div class="dash-alert-content">
                                <strong style="color:var(--green);">{{ session('success') }}</strong>
                            </div>
                        </div>
                        @endif

                        @if (session('warning'))
                        <div class="dash-alert" style="border-color:var(--accent);background:rgba(184,134,11,0.06);margin-bottom:20px;">
                            <div class="dash-alert-dot" style="background:var(--accent);animation:none;"></div>
                            <div class="dash-alert-content">
                                <strong style="color:var(--accent);">{{ session('warning') }}</strong>
                            </div>
                        </div>
                        @endif

                        @if (session('error'))
                        <div class="dash-alert" style="border-color:var(--red);background:rgba(192,57,43,0.06);margin-bottom:20px;">
                            <div class="dash-alert-dot" style="background:var(--red);animation:none;"></div>
                            <div class="dash-alert-content">
                                <strong style="color:var(--red);">{{ session('error') }}</strong>
                            </div>
                        </div>
                        @endif

                        @if ($errors->any())
                        <div class="dash-alert" style="border-color:var(--red);background:rgba(192,57,43,0.06);margin-bottom:20px;">
                            <div class="dash-alert-dot" style="background:var(--red);animation:none;"></div>
                            <div class="dash-alert-content">
                                <strong style="color:var(--red);">Periksa kembali isian form.</strong>
                            </div>
                        </div>
                        @endif

                        <div class="dash-section-head" style="margin-bottom:24px;">
                            <h2 class="dash-section-title" style="font-size:16px;">Alamat Tersimpan</h2>
                            <button class="ofc-btn ofc-btn--primary" onclick="openAddressModal()">+ Tambah Alamat</button>
                        </div>

                        @if ($addresses->isEmpty())
                        <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;width:100%;min-height:200px;padding:48px 20px;color:var(--ink-muted);background:var(--bg-card);border-radius:var(--radius);border:1px solid var(--line);">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" style="margin-bottom:16px;opacity:0.4;">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                            <p style="font-size:14px;">Belum ada alamat tersimpan.</p>
                            <button class="ofc-btn ofc-btn--primary" onclick="openAddressModal()" style="margin-top:12px;">Tambah Alamat Baru</button>
                        </div>
                        @else
                        <div class="address-list">
                            @foreach ($addresses as $address)
                            <div class="address-card {{ $address->is_default ? 'address-card--main' : '' }}">
                                <div class="address-card-header">
                                    <span class="address-label">{{ $address->label }}</span>
                                    <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                                        @if ($address->is_default)
                                        <span class="address-default-badge">Utama</span>
                                        @else
                                        <form method="POST" action="{{ route('dashboard.alamat.default', $address->id) }}" style="display:inline;">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="address-set-main">Jadikan Utama</button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                                <p class="address-name">{{ $address->receiver_name }} · {{ $address->phone }}</p>
                                <p class="address-detail">{{ $address->address }}, {{ $address->district ? $address->district . ', ' : '' }}{{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}</p>
                                @if ($address->catatan)
                                <p class="address-catatan" style="font-size:12px;color:var(--ink-muted);margin-top:4px;">Catatan: {{ $address->catatan }}</p>
                                @endif
                                <div class="address-actions">
                                    <button class="address-btn" onclick="openAddressModal({{ $address->id }})">Edit</button>
                                    <form method="POST" action="{{ route('dashboard.alamat.delete', $address->id) }}" style="display:inline;" class="delete-address-form">
                                        @csrf
                        @method('DELETE')
                                        <button type="button" class="address-btn address-btn--danger btn-delete-address">Hapus</button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div><!-- /panel-address -->

    <!-- ADDRESS MODAL -->
    <div class="modal-overlay" id="addressModal" onclick="if(event.target===this)closeAddressModal()">
        <div class="modal-box" style="max-width:560px;">
            <div class="modal-header">
                <h3 id="addressModalTitle">Tambah Alamat Baru</h3>
                <button class="modal-close" onclick="closeAddressModal()">✕</button>
            </div>
            <div class="modal-body">
                <form method="POST" action="" id="addressForm">
                    @csrf
                    <input type="hidden" name="_method" id="addressFormMethod" value="POST">
                    <div class="pform-grid">
                        <div class="form-group">
                            <label for="addr_label">Label Alamat <span style="color:var(--red);">*</span></label>
                            <input type="text" id="addr_label" name="label" placeholder="Rumah / Kantor / Lainnya" required>
                        </div>
                        <div class="form-group">
                            <label for="addr_receiver_name">Nama Penerima <span style="color:var(--red);">*</span></label>
                            <input type="text" id="addr_receiver_name" name="receiver_name" placeholder="Nama lengkap" required>
                        </div>
                        <div class="form-group">
                            <label for="addr_phone">Nomor Telepon <span style="color:var(--red);">*</span></label>
                            <input type="tel" id="addr_phone" name="phone" placeholder="08xx-xxxx-xxxx" required>
                        </div>
                        <div class="form-group">
                            <label for="addr_province">Provinsi <span style="color:var(--red);">*</span></label>
                            <select id="addr_province" name="province" required>
                                <option value="">Pilih Provinsi</option>
                                <option value="DKI Jakarta">DKI Jakarta</option>
                                <option value="Jawa Barat">Jawa Barat</option>
                                <option value="Jawa Tengah">Jawa Tengah</option>
                                <option value="DI Yogyakarta">DI Yogyakarta</option>
                                <option value="Jawa Timur">Jawa Timur</option>
                                <option value="Banten">Banten</option>
                                <option value="Bali">Bali</option>
                                <option value="Sumatera Utara">Sumatera Utara</option>
                                <option value="Sumatera Selatan">Sumatera Selatan</option>
                                <option value="Kalimantan Timur">Kalimantan Timur</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="addr_city">Kota/Kabupaten <span style="color:var(--red);">*</span></label>
                            <input type="text" id="addr_city" name="city" placeholder="Jakarta Selatan" required>
                        </div>
                        <div class="form-group">
                            <label for="addr_district">Kecamatan</label>
                            <input type="text" id="addr_district" name="district" placeholder="Kebayoran Baru" required>
                        </div>
                        <div class="form-group">
                            <label for="addr_postal_code">Kode Pos <span style="color:var(--red);">*</span></label>
                            <input type="text" id="addr_postal_code" name="postal_code" placeholder="12190" required>
                        </div>
                        <div class="form-group" style="display:flex;align-items:flex-end;padding-bottom:4px;">
                            <label class="addr-checkbox-label">
                                <input type="checkbox" name="is_default" id="addr_is_default" value="1">
                                <span class="addr-checkbox-mark"></span>
                                Jadikan utama
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="addr_catatan">Catatan <span style="color:var(--ink-muted);font-weight:400;">(opsional)</span></label>
                        <textarea id="addr_catatan" name="catatan" style="height:54px;" placeholder="Contoh: dekat warung Bu Indah, pagar hijau, patokan lainnya..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="addr_address">Alamat Lengkap <span style="color:var(--red);">*</span></label>
                        <textarea id="addr_address" name="address" style="height:80px;" placeholder="Jl. Nama Jalan No.xx, RT/RW, Kelurahan, dsb. (ketik alamat lengkap)" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Peta Lokasi <span style="color:var(--red);">*</span></label>
                        <div class="addr-map-wrap">
                            <div id="addrMap" class="addr-map"></div>
                        </div>
                        <div class="addr-geo-hint" id="addrGeoHint">Klik peta atau geser marker untuk menentukan titik lokasi. Titik lokasi wajib diisi sebelum menyimpan.</div>
                    </div>
                    <input type="hidden" name="area_id" id="addr_area_id">
                    <input type="hidden" name="latitude" id="addr_lat">
                    <input type="hidden" name="longitude" id="addr_lng">
                    <div style="display:flex;gap:12px;margin-top:8px;">
                        <button type="submit" class="btn-submit" id="addressSubmitBtn">Simpan Alamat</button>
                        <button type="button" class="btn-outline-light" style="padding:12px 24px;border-radius:var(--radius-sm);font-size:13px;" onclick="closeAddressModal()">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    var addressData = @json($addresses);
    var addressUpdateBase = '{{ url('dashboard/alamat') }}';
    var addrGeocodeUrl = '{{ route('dashboard.alamat.geocode') }}';
    var addrReverseUrl = '{{ route('dashboard.alamat.reverse') }}';
    var addrAreaUrl = '{{ route('dashboard.alamat.area') }}';
    var addrDetailsUrl = '{{ route('dashboard.alamat.details') }}';
    var csrfToken = document.querySelector('meta[name=csrf-token]') ? document.querySelector('meta[name=csrf-token]').content : '';

    // ---------- MAP ----------
    var map = null;
    var marker = null;

    function ensureMap() {
        if (map) return;
        map = L.map('addrMap', { scrollWheelZoom: false }).setView([-6.5950, 106.8166], 13);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);
        map.on('click', function(e) {
            setMarker(e.latlng.lat, e.latlng.lng);
            reverseGeocode(e.latlng.lat, e.latlng.lng);
        });
    }

    function setMarker(lat, lng) {
        if (!map) return;
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            marker.on('dragend', function() {
                var p = marker.getLatLng();
                reverseGeocode(p.lat, p.lng);
            });
        }
        map.setView([lat, lng], 16);
        document.getElementById('addr_lat').value = lat;
        document.getElementById('addr_lng').value = lng;
    }

    function removeMarker() {
        if (marker && map) {
            map.removeLayer(marker);
            marker = null;
        }
    }

    function reverseGeocode(lat, lng) {
        var hint = document.getElementById('addrGeoHint');
        hint.textContent = 'Mencari lokasi...';
        fetch(addrReverseUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ lat: lat, lng: lng })
        }).then(function(r) { return r.json(); }).then(function(res) {
            hint.textContent = 'Klik peta atau geser marker untuk menentukan titik lokasi.';
            if (res.error) {
                Swal.fire({ icon: 'warning', title: 'Lokasi tidak ditemukan', text: res.error });
                return;
            }
            applyPlace(res.result);
        }).catch(function() {
            hint.textContent = 'Klik peta untuk menentukan titik lokasi.';
        });
    }

    function setProvinceSelect(value) {
        var sel = document.getElementById('addr_province');
        if (!value) return '';
        var exists = false;
        for (var i = 0; i < sel.options.length; i++) {
            if (sel.options[i].value === value) { exists = true; break; }
        }
        if (!exists) {
            var opt = document.createElement('option');
            opt.value = value;
            opt.textContent = value;
            sel.appendChild(opt);
        }
        sel.value = value;
        return value;
    }

    function applyPlace(p) {
        if (!p) return;
        document.getElementById('addr_address').value = p.address || '';
        setProvinceSelect(p.province || '');
        document.getElementById('addr_city').value = p.city || '';
        document.getElementById('addr_district').value = p.district || p.subdistrict || p.city || '';
        document.getElementById('addr_postal_code').value = p.postal_code || '';
        if (p.lat && p.lng) {
            setMarker(p.lat, p.lng);
        }
        resolveArea(true);
    }

    function resolveArea(overwriteFields) {
        var district = document.getElementById('addr_district').value;
        var city = document.getElementById('addr_city').value;
        var province = document.getElementById('addr_province').value;
        var postal = document.getElementById('addr_postal_code').value;
        if (!city && !district && !province) return;
        fetch(addrAreaUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ district: district, city: city, province: province, postal_code: postal })
        }).then(function(r) { return r.json(); }).then(function(res) {
            document.getElementById('addr_area_id').value = res.area_id || '';
            if (overwriteFields && res.area) {
                setProvinceSelect(res.area.province || province);
                document.getElementById('addr_city').value = res.area.city || city;
                document.getElementById('addr_district').value = res.area.district || district;
                document.getElementById('addr_postal_code').value = res.area.postal_code || postal;
            }
        });
    }

    ['addr_province', 'addr_city', 'addr_district', 'addr_postal_code'].forEach(function(fid) {
        document.getElementById(fid).addEventListener('change', function() {
            document.getElementById('addr_area_id').value = '';
            resolveArea(false);
        });
    });

    function fetchPlaceDetails(placeId, cb) {
        fetch(addrDetailsUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ place_id: placeId })
        }).then(function(r) { return r.json(); }).then(function(res) {
            cb(res.result || null);
        }).catch(function() {
            cb(null);
        });
    }

    function placeMarkerFromStored(addr) {
        var q = [addr.address, addr.district, addr.city, addr.province, addr.postal_code].filter(Boolean).join(', ');
        var hint = document.getElementById('addrGeoHint');
        hint.textContent = 'Mencari lokasi tersimpan...';
        fetch(addrGeocodeUrl + '?q=' + encodeURIComponent(q), { headers: { 'X-CSRF-TOKEN': csrfToken } })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                hint.textContent = 'Klik peta atau geser marker untuk menentukan titik lokasi.';
                var first = (res.results || [])[0];
                if (!first) { hint.textContent = 'Klik peta untuk menentukan titik lokasi.'; return; }
                if (first.place_id) {
                    fetchPlaceDetails(first.place_id, function(detail) {
                        if (detail && detail.lat && detail.lng) {
                            setMarker(detail.lat, detail.lng);
                        }
                    });
                } else if (first.lat && first.lng) {
                    setMarker(first.lat, first.lng);
                }
            })
            .catch(function() {
                hint.textContent = 'Klik peta untuk menentukan titik lokasi.';
            });
    }

    // ---------- MODAL ----------
    function openAddressModal(id) {
        var form = document.getElementById('addressForm');
        var title = document.getElementById('addressModalTitle');
        var method = document.getElementById('addressFormMethod');
        var submitBtn = document.getElementById('addressSubmitBtn');

        if (id) {
            var addr = addressData.find(function(a) { return a.id === id; });
            if (!addr) return;
            title.textContent = 'Edit Alamat';
            method.value = 'PUT';
            form.action = addressUpdateBase + '/' + id;
            submitBtn.textContent = 'Simpan Perubahan';
            document.getElementById('addr_label').value = addr.label || '';
            document.getElementById('addr_receiver_name').value = addr.receiver_name || '';
            document.getElementById('addr_phone').value = addr.phone || '';
            setProvinceSelect(addr.province || '');
            document.getElementById('addr_city').value = addr.city || '';
            document.getElementById('addr_district').value = addr.district || '';
            document.getElementById('addr_postal_code').value = addr.postal_code || '';
            document.getElementById('addr_catatan').value = addr.catatan || '';
            document.getElementById('addr_address').value = addr.address || '';
            document.getElementById('addr_area_id').value = addr.area_id || '';
            document.getElementById('addr_lat').value = addr.latitude || '';
            document.getElementById('addr_lng').value = addr.longitude || '';
            document.getElementById('addr_is_default').checked = addr.is_default ? true : false;
        } else {
            title.textContent = 'Tambah Alamat Baru';
            method.value = 'POST';
            form.action = '{{ route("dashboard.alamat.store") }}';
            submitBtn.textContent = 'Simpan Alamat';
            form.reset();
            document.getElementById('addr_area_id').value = '';
            document.getElementById('addr_lat').value = '';
            document.getElementById('addr_lng').value = '';
            removeMarker();
        }

        ensureMap();
        document.getElementById('addressModal').classList.add('open');
        setTimeout(function() {
            if (map) map.invalidateSize();
        }, 300);

        if (id && addr) {
            if (addr.latitude && addr.longitude) {
                setMarker(parseFloat(addr.latitude), parseFloat(addr.longitude));
            } else {
                placeMarkerFromStored(addr);
            }
        }
    }

    function closeAddressModal() {
        document.getElementById('addressModal').classList.remove('open');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAddressModal();
        }
    });

    // Marker wajib sebelum menyimpan
    document.getElementById('addressForm').addEventListener('submit', function(e) {
        var lat = document.getElementById('addr_lat').value;
        var lng = document.getElementById('addr_lng').value;
        if (!lat || !lng) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Pilih Titik Lokasi',
                text: 'Silakan klik peta atau geser marker untuk menentukan titik lokasi sebelum menyimpan alamat.'
            });
        }
    });

    // Delete address confirmation with SweetAlert2
    document.querySelectorAll('.btn-delete-address').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            var form = this.closest('.delete-address-form');
            Swal.fire({
                title: 'Hapus alamat ini?',
                text: 'Alamat yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#C0392B',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then(function(result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush