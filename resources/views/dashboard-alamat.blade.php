@extends('layouts.dashboard')

@section('title', 'Alamat Pengiriman — CHRISBALE')

@section('dashboard-content')
                    <div class="dash-panel" id="panel-address">
                        <h2 class="dash-section-title" style="margin-bottom:28px;">Alamat Pengiriman</h2>

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
                            <input type="text" id="addr_district" name="district" placeholder="Kebayoran Baru">
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
                        <label for="addr_address">Alamat Lengkap <span style="color:var(--red);">*</span></label>
                        <textarea id="addr_address" name="address" style="height:80px;" placeholder="Jl. Nama Jalan No.xx, RT/RW, Kelurahan" required></textarea>
                    </div>
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
<script>
    var addressData = @json($addresses);
    var addressUpdateBase = '{{ url('dashboard/alamat') }}';

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
            document.getElementById('addr_province').value = addr.province || '';
            document.getElementById('addr_city').value = addr.city || '';
            document.getElementById('addr_district').value = addr.district || '';
            document.getElementById('addr_postal_code').value = addr.postal_code || '';
            document.getElementById('addr_address').value = addr.address || '';
            document.getElementById('addr_is_default').checked = addr.is_default ? true : false;
        } else {
            title.textContent = 'Tambah Alamat Baru';
            method.value = 'POST';
            form.action = '{{ route("dashboard.alamat.store") }}';
            submitBtn.textContent = 'Simpan Alamat';
            form.reset();
        }

        document.getElementById('addressModal').classList.add('open');
    }

    function closeAddressModal() {
        document.getElementById('addressModal').classList.remove('open');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAddressModal();
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