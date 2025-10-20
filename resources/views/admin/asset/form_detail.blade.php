@extends('layouts.admin.main')
@section('content')
    <style>
        .form-section {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e3e6f0;
        }

        .form-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .section-title {
            font-size: 1.1rem;
            color: #4e73df;
            margin-bottom: 15px;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 10px;
        }

        .form-label {
            font-weight: 500;
            color: #4e4e4e;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #d1d3e2;
            transition: all 0.3s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        }

        .input-group-text {
            background-color: #f8f9fc;
            border-radius: 8px 0 0 8px;
        }

        .btn-secondary-light {
            background-color: #f8f9fc;
            color: #858796;
            border: 1px solid #d1d3e2;
            border-radius: 8px;
            padding: 8px 15px;
            font-weight: 400;
            transition: all 0.3s;
        }

        .btn-secondary-light:hover {
            background-color: #eaecf4;
            color: #6c757d;
        }

        .map-preview {
            height: 500px;
            background-color: #f8f9fc;
            border: 2px dashed #d1d3e2;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #858796;
            margin-top: 10px;
        }

        .error-message {
            color: #e74a3b;
            font-size: 0.85rem;
            margin-top: 5px;
            display: none;
        }

        #map {
            width: 100%;
            height: 495px;
            /* wajib, sesuaikan kebutuhan */
            border: 1px solid #ccc;
            border-radius: 8px;
        }
    </style>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <form id="form-update">
                    <div class="modal-body">
                        <div class="form-section">
                            <div class="row">
                                <div class="col-5">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <div class="form-group">
                                                <label for="create-kode_asset" class="form-label" fw-semibold"><i
                                                        class="bi bi-key text-primary me-2"></i>Kode Asset</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-key"></i></span>
                                                    <input type="text" id="create-kode_asset" name="kode_asset"
                                                        class="form-control" placeholder="Kode akan digenerate otomatis"
                                                        value="{{ $asset->kode_asset }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="nama_asset" class="form-label" fw-semibold"><i
                                                        class="bi bi-archive text-primary me-2"></i>Nama Asset</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light"><i
                                                            class="bi bi-archive"></i></span>
                                                    <input type="text" class="form-control" id="update-nama_asset"
                                                        name="nama_asset" placeholder="Masukkan Nama Asset"
                                                        value="{{ $asset->nama_asset }}" readonly>
                                                </div>
                                                <div id="update-nama_asset-msg"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="jenis_asset_id" class="form-label required-field">Jenis
                                                    Asset</label>
                                                <select class="form-control" id="update-jenis_asset_id"
                                                    name="jenis_asset_id" disabled>
                                                    <option value="" selected disabled>Pilih Jenis Asset</option>
                                                    @foreach ($jenis_asset as $item)
                                                        <option value="{{ $item->id_jenis_asset }}"
                                                            {{ $item->id_jenis_asset == $asset->jenis_asset_id ? 'selected' : '' }}>
                                                            {{ $item->jenis_asset }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div id="update-jenis_asset_id-msg"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="penanggung_jawab_id"
                                                    class="form-label required-field">Penanggung
                                                    Jawab</label>
                                                <select class="form-control" id="update-penanggung_jawab_id"
                                                    name="penanggung_jawab_id" disabled>
                                                    <option value="" selected disabled>Pilih Penanggung Jawab</option>
                                                    @foreach ($penanggung_jawab as $item)
                                                        <option value="{{ $item->id_penanggung_jawab }}"
                                                            {{ $item->id_penanggung_jawab == $asset->penanggung_jawab_id ? 'selected' : '' }}>
                                                            {{ $item->nama_penanggung_jawab }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div id="update-penanggung_jawab_id-msg"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group mb-3">
                                                <label for="jalan_id" class="form-label required-field">Lokasi Jalan</label>
                                                <select class="form-control" id="update-jalan_id" name="jalan_id" disabled>
                                                    <option value="" selected disabled>Pilih Lokasi Jalan</option>
                                                    @foreach ($jalan as $item)
                                                        <option value="{{ $item->id_jalan }}"
                                                            {{ $item->id_jalan == $asset->jalan_id ? 'selected' : '' }}>
                                                            {{ $item->nama_jalan }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div id="update-jalan_id-msg"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="kondisi" class="form-label required-field">Kondisi</label>
                                                <select class="form-control" id="create-kondisi" name="kondisi" disabled>
                                                    <option value="baik"
                                                        {{ $asset->kondisi == 'baik' ? 'selected' : '' }}>Baik</option>
                                                    <option value="rusak ringan"
                                                        {{ $asset->kondisi == 'rusak ringan' ? 'selected' : '' }}>Rusak
                                                        Ringan</option>
                                                    <option value="rusak parah"
                                                        {{ $asset->kondisi == 'rusak parah' ? 'selected' : '' }}>Rusak
                                                        Parah
                                                    </option>
                                                </select>
                                                <div id="create-kondisi-msg"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="kecamatan_id"
                                                    class="form-label required-field">Kecamatan</label>
                                                <select class="form-control" id="create-kecamatan_id" name="kecamatan_id"
                                                    disabled>
                                                    <option value="" selected disabled>Pilih Kecamatan</option>
                                                    @foreach ($kecamatan as $item)
                                                        <option value="{{ $item->id_kecamatan }}"
                                                            {{ $item->id_kecamatan == $asset->kecamatan_id ? 'selected' : '' }}>
                                                            {{ $item->nama_kecamatan }}</option>
                                                    @endforeach
                                                </select>
                                                <div id="create-kecamatan_id-msg"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="kelurahan_id"
                                                    class="form-label required-field">Kelurahan</label>
                                                <select class="form-control" id="create-kelurahan_id" name="kelurahan_id"
                                                    disabled>
                                                    <option value="" selected disabled>Pilih Kelurahan</option>
                                                    @foreach ($kelurahan as $item)
                                                        <option value="{{ $item->id_kelurahan }}"
                                                            {{ $item->id_kelurahan == $asset->kelurahan_id ? 'selected' : '' }}>
                                                            {{ $item->nama_kelurahan }}</option>
                                                    @endforeach
                                                </select>
                                                <div id="create-kelurahan_id-msg"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-2">
                                                <label for="koordinat" class="form-label">Koordinat (Latitude,
                                                    Longitude)</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                                    <input type="text" class="form-control" id="update-koordinat"
                                                        name="koordinat" value="{{ $asset->koordinat }}" disabled>
                                                    <button class="btn btn-outline-primary" type="button"
                                                        id="btn-detect-location" hidden>
                                                        <i class="bi bi-geo me-2"></i> Deteksi Lokasi
                                                    </button>
                                                </div>
                                                <div id="location-status" class="location-status status-info">
                                                </div>
                                                <div id="update-koordinat-msg"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-7">
                                    <div class="map-preview">
                                        <div id="map"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-section">
                                        <div class="form-group mb-3">
                                            <label for="update-foto_asset" class="form-label">Foto Asset
                                            </label>
                                            @if ($dokumen_asset)
                                                @foreach ($dokumen_asset as $dok)
                                                    <img src="{{ asset('storage/' . $dok->file_asset) }}"
                                                        class="img-thumbnail m-1" style="height:100px;">
                                                @endforeach
                                            @else
                                                <img src="{{ optional($asset)->foto_asset
                                                    ? asset('storage/' . $asset->foto_asset)
                                                    : asset('assets/global/img/kardus.png') }}"
                                                    alt="Foto Laporan" class="rounded shadow-sm border"
                                                    style="width:150px; height:120px; object-fit:cover;">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="update-id_asset" value='{{ $asset->id_asset }}'>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary-light me-2">
                            <i class="bi bi-skip-backward-fill me-2"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        let map = null;
        let marker = null;

        // Fungsi inisialisasi peta
        function initMap() {
            if (!map) {
                map = L.map('map', {
                    center: [1.095728, 104.495919],
                    zoom: 15,
                    attributionControl: false
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                const koordinatInput = document.getElementById('update-koordinat').value.trim();
                if (koordinatInput) {
                    const coords = koordinatInput.split(',');
                    if (coords.length === 2) {
                        const lat = parseFloat(coords[0]);
                        const lng = parseFloat(coords[1]);
                        if (!isNaN(lat) && !isNaN(lng)) {
                            updateLocation(lat, lng, 'db'); // langsung tampilkan marker dari DB
                        }
                    }
                }
            } else {
                setTimeout(function() {
                    map.invalidateSize();
                }, 100);
            }
        }

        function updateLocation(lat, lng, source) {
            if (!map) {
                initMap();
            }

            document.getElementById('update-koordinat').value = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;

            if (marker) {
                map.removeLayer(marker);
            }

            const myIcon = L.icon({
                iconUrl: '/assets/global/img/sign.png', // ganti sesuai path marker kamu
                iconSize: [22, 25],
            });

            marker = L.marker([lat, lng], {
                    icon: myIcon
                })
                .addTo(map)
                .bindPopup(`Lokasi : ${lat.toFixed(6)}, ${lng.toFixed(6)}`)
                .openPopup();

            map.setView([lat, lng], 15);

            // Update status
            const statusElement = document.getElementById('location-status');
            const statusMessage = document.getElementById('status-message');

            if (source === 'gps') {
                statusElement.className = 'location-status status-success';
                statusMessage.innerHTML =
                    `<i class="bi bi-check-circle"></i> Lokasi berhasil dideteksi: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
            } else if (source === 'manual') {
                statusElement.className = 'location-status status-success';
                statusMessage.innerHTML =
                    `<i class="bi bi-check-circle"></i> Lokasi dipilih: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
            } else if (source === 'db') {
                statusElement.className = 'location-status status-info';
                statusMessage.innerHTML =
                    `<i class="bi bi-map"></i> Lokasi dari database: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
            }
        }

        // Event listener untuk input koordinat manual
        document.getElementById('update-koordinat').addEventListener('change', function() {
            const value = this.value.trim();
            const coords = value.split(',');

            if (coords.length === 2) {
                const lat = parseFloat(coords[0]);
                const lng = parseFloat(coords[1]);

                if (!isNaN(lat) && !isNaN(lng)) {
                    updateLocation(lat, lng, 'manual');
                } else {
                    const statusElement = document.getElementById('location-status');
                    const statusMessage = document.getElementById('status-message');
                    statusElement.className = 'location-status status-error';
                    statusMessage.innerHTML =
                        '<i class="bi bi-exclamation-triangle"></i> Format koordinat tidak valid';
                }
            } else {
                const statusElement = document.getElementById('location-status');
                const statusMessage = document.getElementById('status-message');
                statusElement.className = 'location-status status-error';
                statusMessage.innerHTML =
                    '<i class="bi bi-exclamation-triangle"></i> Format koordinat harus: latitude, longitude';
            }
        });

        initMap();
    </script>
@endpush
