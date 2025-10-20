@extends('layouts.out.main')

@section('title', $title)
@push('style')
    <style>
        #map {
            width: 100%;
            height: 350px;
            border-radius: 10px;
        }

        @media (min-width: 992px) {
            #map {
                height: 495px;
            }
        }

        h1.display-5 {
            font-size: 2.5rem;
            font-weight: bold;
        }

        h2,
        h3 {
            margin-top: 10px;
            font-weight: normal;
        }

        .card {
            border-radius: 10px;
        }

        .card-title {
            font-weight: bold;
        }

        @keyframes pulseAnim {
            0% {
                transform: scale(0.8);
                opacity: 0.7;
            }

            70% {
                transform: scale(1.3);
                opacity: 0;
            }

            100% {
                opacity: 0;
            }
        }

        .upload-box {
            border: 2px dashed #f8a5c2;
            border-radius: 12px;
            padding: 10px;
            text-align: center;
            cursor: pointer;
            transition: 0.3s;
            background: #fff;
        }

        .upload-box.dragover {
            background: #ffe6eb;
        }

        .upload-box img {
            max-height: 180px;
            margin-top: 5px;
            border-radius: 10px;
            object-fit: cover;
        }

        .upload-icon {
            font-size: 60px;
            color: #f78fb3;
        }

        .hidden-input {
            display: none;
        }
    </style>
@endpush
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <form id="form-update">
                    <div class="modal-body">
                        <div class="form-section">
                            <div class="row">
                                <div class="col-12 col-lg-5">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <div class="form-group">
                                                <label for="create-kode_asset" class="form-label">Kode Asset</label>
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
                                                <label for="nama_asset" class="form-label required-field">Nama
                                                    Asset</label>
                                                <input type="text" class="form-control" id="update-nama_asset"
                                                    name="nama_asset" placeholder="Masukkan Nama Asset"
                                                    value="{{ $asset->nama_asset }}" readonly>
                                                <div id="update-nama_asset-msg"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="jenis_asset_id" class="form-label required-field">Jenis
                                                    Asset</label>
                                                <select class="form-control" id="update-jenis_asset_id"
                                                    name="jenis_asset_id" disabled>
                                                    <option value="" selected disabled>Pilih Jenis Asset
                                                    </option>
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
                                                    <option value="" selected disabled>Pilih Penanggung Jawab
                                                    </option>
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
                                                <label for="jalan_id" class="form-label required-field">Lokasi
                                                    Jalan</label>
                                                <select class="form-control" id="update-jalan_id" name="jalan_id" disabled>
                                                    <option value="" selected disabled>Pilih Lokasi Jalan
                                                    </option>
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
                                                        {{ $asset->kondisi == 'baik' ? 'selected' : '' }}>Baik
                                                    </option>
                                                    <option value="rusak ringan"
                                                        {{ $asset->kondisi == 'rusak ringan' ? 'selected' : '' }}>
                                                        Rusak
                                                        Ringan</option>
                                                    <option value="rusak parah"
                                                        {{ $asset->kondisi == 'rusak parah' ? 'selected' : '' }}>
                                                        Rusak
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
                                                    <option value="" selected disabled>Pilih Kecamatan
                                                    </option>
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
                                                    <option value="" selected disabled>Pilih Kelurahan
                                                    </option>
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
                                            </div>
                                            <div id="update-koordinat-msg"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-7 mt-3 mt-lg-0">
                                    <div class="map-preview">
                                        <div id="map"></div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2">
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
    @endsection
    @push('script')
        <script>
            let map = null;
            let marker = null;

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
            }

            initMap();

            function capitalizeEachWord(str) {
                return str.replace(/\b\w/g, char => char.toUpperCase());
            }
        </script>
    @endpush
