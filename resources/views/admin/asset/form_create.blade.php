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

        .required-field::after {
            content: " *";
            color: #e74a3b;
        }

        .map-preview {
            height: 550px;
            background-color: #f8f9fc;
            border: 2px dashed #d1d3e2;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #858796;
            margin-top: 2px;
        }

        .error-message {
            color: #e74a3b;
            font-size: 0.85rem;
            margin-top: 5px;
            display: none;
        }

        #map {
            width: 100%;
            height: 550px;
            /* wajib, sesuaikan kebutuhan */
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        /* Samakan tinggi & border select2 dengan input bootstrap */
        .select2-container .select2-selection--single {
            height: calc(2.5rem + 2px) !important;
            /* tinggi sama dengan .form-control */
            padding: 0.375rem 0.75rem;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            /* sesuai bootstrap rounded */
            display: flex;
            align-items: center;
        }

        /* teks dalam select2 */
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 1.5 !important;
            color: #212529;
        }

        /* icon panah dropdown */
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100% !important;
            right: 10px;
        }
    </style>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="col-md-12">
                    <div class="map-preview">
                        <div id="map"></div>
                    </div>
                </div>
                <form id="form-create">
                    <div class="modal-body">
                        <div class="form-section">
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-12 mb-3">
                                            <div class="form-group">
                                                <label for="create-kode_asset" class="form-label fw-semibold"><i
                                                        class="bi bi-key text-primary me-2"></i>Kode Asset *</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-key"></i></span>
                                                    <input type="text" id="create-kode_asset" name="kode_asset"
                                                        class="form-control" placeholder="Kode akan digenerate otomatis"
                                                        readonly>
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        id="generate-kode">
                                                        <i class="bi bi-arrow-repeat me-2"></i>
                                                    </button>
                                                </div>
                                                <div id="create-kode_asset-msg" class="form-text">Kode asset akan
                                                    digenerate
                                                    otomatis</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <div class="form-group mb-3">
                                                <label for="create-kode_asset" class="form-label fw-semibold"><i
                                                        class="bi bi-archive text-primary me-2"></i>Nama Asset *</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light"><i
                                                            class="bi bi-archive"></i></span>
                                                    <input type="text" class="form-control" id="create-nama_asset"
                                                        name="nama_asset" placeholder="Masukkan Nama Asset">
                                                </div>
                                                <div id="create-nama_asset-msg"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <div class="form-group mb-3">
                                                <label for="create-jenis_asset_id" class="form-label fw-semibold"><i
                                                        class="bi bi-archive text-primary me-2"></i>Jenis
                                                    Asset *</label>
                                                <select class="form-select" id="create-jenis_asset_id"
                                                    name="jenis_asset_id">
                                                    <option value="" selected disabled>Pilih</option>
                                                    @foreach ($jenis_asset as $item)
                                                        <option value="{{ $item->id_jenis_asset }}">{{ $item->jenis_asset }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div id="create-jenis_asset_id-msg"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <div class="form-group mb-3">
                                                <label for="create-nama_jalan" class="form-label fw-semibold"><i
                                                        class="bi bi-person text-primary me-2"></i>Penanggung
                                                    Jawab *</label>
                                                <select class="form-select" id="create-penanggung_jawab_id"
                                                    name="penanggung_jawab_id">
                                                    <option value="" selected disabled>Pilih</option>
                                                    @foreach ($penanggung_jawab as $item)
                                                        <option value="{{ $item->id_penanggung_jawab }}">
                                                            {{ $item->nama_penanggung_jawab }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div id="create-penanggung_jawab_id-msg"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12">
                                            <div class="form-group mb-3">
                                                <label for="create-nama_jalan" class="form-label fw-semibold"><i
                                                        class="bi bi-signpost-fill text-primary me-2"></i>Lokasi
                                                    Jalan *</label>
                                                <select class="form-select" id="create-jalan_id" name="jalan_id">
                                                    <option value="" selected disabled>Pilih</option>
                                                    @foreach ($jalan as $item)
                                                        <option value="{{ $item->id_jalan }}">{{ $item->nama_jalan }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div id="create-jalan_id-msg"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12">
                                            <div class="form-group mb-3">
                                                <label for="create-nama_jalan" class="form-label fw-semibold"><i
                                                        class="bi bi-bookmark-check text-primary me-2"></i>
                                                    Kondisi *</label>
                                                <select class="form-select" id="create-kondisi" name="kondisi">
                                                    <option value="" selected disabled>Pilih</option>
                                                    <option value="baik">Baik</option>
                                                    <option value="rusak ringan">Rusak Ringan</option>
                                                    <option value="rusak parah">Rusak Parah</option>
                                                </select>
                                                <div id="create-kondisi-msg"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12">
                                            <div class="form-group mb-3">
                                                <label for="create-kecamatan_id" class="form-label fw-semibold"><i
                                                        class="bi bi-building text-primary me-2"></i>Kecamatan *</label>
                                                <select class="form-select select2" id="create-kecamatan_id"
                                                    name="kecamatan_id">
                                                    <option value="" selected disabled>Pilih</option>
                                                    @foreach ($kecamatan as $item)
                                                        <option value="{{ $item->id_kecamatan }}">
                                                            {{ $item->nama_kecamatan }}</option>
                                                    @endforeach
                                                </select>
                                                <div id="create-kecamatan_id-msg"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12">
                                            <div class="form-group mb-3">
                                                <label for="create-kelurahan_id" class="form-label fw-semibold"><i
                                                        class="bi bi-building text-primary me-2"></i>Kelurahan *</label>
                                                <select class="form-select select2" id="create-kelurahan_id"
                                                    name="kelurahan_id" disabled>
                                                    <option value="" selected disabled>Pilih</option>
                                                </select>
                                                <div id="create-kelurahan_id-msg"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-2">
                                                <label for="create-koordinat" class="form-label fw-semibold"><i
                                                        class="bi bi-geo-alt text-primary me-2"></i>Koordinat * (Latitude,
                                                    Longitude)</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                                    <input type="text" class="form-control" id="create-koordinat"
                                                        name="koordinat" placeholder="Contoh: 1.095728, 104.495919">
                                                    <button class="btn btn-outline-primary" type="button"
                                                        id="btn-detect-location">
                                                        <i class="bi bi-geo me-2"></i>
                                                    </button>
                                                </div>
                                                <div class="form-text">Format: latitude, longitude (pisahkan dengan koma)
                                                    ||
                                                    *
                                                    <span id="status-message" class="form-text"
                                                        style="font-size: 8pt">Klik
                                                        tombol
                                                        deteksi
                                                        lokasi untuk mendapatkan koordinat</span>
                                                </div>
                                                <div id="location-status" class="location-status status-info">
                                                </div>
                                                <div id="create-koordinat-msg"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-section">
                                                <div class="form-group mb-3">
                                                    <label for="create-foto_asset" class="form-label fw-semibold"><i
                                                            class="bi bi-image text-primary me-2"></i>
                                                        Upload Foto Asset *(Maks ukuran file
                                                        2MB)</small></span>
                                                    </label>
                                                    <!-- tambahkan multiple -->
                                                    <input type="file" id="create-foto_asset" name="foto_asset[]"
                                                        class="form-control" accept="image/*" multiple>
                                                    <div id="create-foto_asset-msg"></div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-12">
                                            <div class="form-section">
                                                <div class="form-group mb-3">
                                                    <label for="create-foto_asset" class="form-label">Upload Foto Asset
                                                        <span class="text-danger">*<small> (Maks ukuran
                                                                file 2MB)</small></span></label>
                                                    <input type="file" id="create-foto_asset" name="foto_asset"
                                                        class="form-control" accept="image/*">
                                                    <div id="create-foto_asset-msg"></div>
                                                </div>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div id="preview-images" class="d-flex flex-wrap gap-2 mt-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ url('admin/asset') }}" class="btn btn-secondary-light me-2">
                            <i class="bi bi-skip-backward-fill me-2"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-floppy me-2"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>
    <script>
        $('#create-kecamatan_id').select2({
            placeholder: "Pilih",
            width: '100%'
        });

        $('#create-kelurahan_id').select2({
            placeholder: "Pilih",
            width: '100%'
        });

        $('#create-kecamatan_id').on('change', function() {
            let kecamatanId = $(this).val();

            // reset kelurahan
            $('#create-kelurahan_id').empty().append('<option value="">Pilih</option>').prop('disabled',
                true);

            if (kecamatanId) {
                $.ajax({
                    url: `/pengaduan/${kecamatanId}`,
                    type: "GET",
                    success: function(data) {
                        if (data.length > 0) {
                            data.forEach(function(kel) {
                                $('#create-kelurahan_id').append(
                                    `<option value="${kel.id_kelurahan}">${kel.nama_kelurahan}</option>`
                                );
                            });

                            // enable dropdown kelurahan
                            $('#create-kelurahan_id').prop('disabled', false);
                        }
                    }
                });
            }
        });

        const fileInput = document.getElementById('create-foto_asset');
        const previewContainer = document.getElementById('preview-images');

        // simpan file sementara di array
        let selectedFiles = [];

        fileInput.addEventListener('change', function(event) {
            selectedFiles = Array.from(event.target.files); // reset dengan file baru
            renderPreview();
        });

        function renderPreview() {
            previewContainer.innerHTML = ""; // kosongkan dulu

            selectedFiles.forEach((file, index) => {
                if (file && file.type.startsWith("image/")) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        // bungkus pakai div biar ada tombol close
                        let wrapper = document.createElement("div");
                        wrapper.classList.add("position-relative");

                        let img = document.createElement("img");
                        img.src = e.target.result;
                        img.classList.add("img-thumbnail", "me-2");
                        img.style.height = "100px";
                        img.style.objectFit = "cover";

                        // tombol hapus
                        let btn = document.createElement("button");
                        btn.innerHTML = "&times;";
                        btn.type = "button";
                        btn.classList.add("btn", "btn-sm", "btn-danger", "position-absolute");
                        btn.style.top = "0";
                        btn.style.right = "0";

                        btn.addEventListener("click", function() {
                            selectedFiles.splice(index, 1); // hapus dari array
                            updateFileList(); // update input file
                            renderPreview(); // render ulang preview
                        });

                        wrapper.appendChild(img);
                        wrapper.appendChild(btn);
                        previewContainer.appendChild(wrapper);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // fungsi untuk update input file agar sesuai selectedFiles
        function updateFileList() {
            let dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => dataTransfer.items.add(file));
            fileInput.files = dataTransfer.files;
        }

        $('#form-create').submit(function(e) {
            e.preventDefault()

            formMode = 'create'

            const formData = new FormData(this)
            formData.append('_token', getCsrfToken())
            formData.append('_method', 'post')

            callApi(formData)
        })

        function callApi(formData, url = null) {
            const submitLabel = $(`#form-${formMode} button[type="submit"]`).text()

            startSpinSubmitBtn(`#form-${formMode}`)

            $.ajax({
                url: url || "{{ url()->current() }}",
                method: "POST",
                data: formData,
                dataType: "JSON",
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    clearValidationMessage(formMode, 'msg')
                },
                success: function(response) {
                    $(`#form-${formMode}`).trigger('reset')
                    $(`#modal-${formMode}`).modal('hide')
                    showSwal(response.message, response.status)

                    window.location.href = "/admin/asset";
                },
                error: function(response) {
                    handleAjaxJqueryError(response, {
                        formPrefix: formMode,
                        msgSuffix: 'msg'
                    })
                },
                complete: function() {
                    stopSpinSubmitBtn(`#form-${formMode}`, submitLabel)
                }
            });
        }

        let map = null;
        let marker = null;

        // Fungsi inisialisasi peta
        function initMap() {
            if (!map) {
                map = L.map('map').setView([1.095728, 104.495919], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                map.on('click', function(e) {
                    updateLocation(e.latlng.lat, e.latlng.lng, 'manual');
                });
            } else {
                setTimeout(function() {
                    map.invalidateSize();
                }, 100);
            }
        }

        document.getElementById('btn-detect-location').addEventListener('click', function() {
            const statusElement = document.getElementById('location-status');
            const statusMessage = document.getElementById('status-message');

            statusElement.className = 'location-status status-info';
            statusMessage.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i> Mendeteksi lokasi...';

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        updateLocation(lat, lng, 'gps');
                    },
                    function(error) {
                        console.error('Error getting location:', error);

                        // Gunakan lokasi default jika deteksi gagal
                        const defaultLat = 1.095728;
                        const defaultLng = 104.495919;
                        updateLocation(defaultLat, defaultLng, 'default');

                        // Tampilkan pesan error
                        const statusElement = document.getElementById('location-status');
                        const statusMessage = document.getElementById('status-message');
                        statusElement.className = 'location-status status-error';

                        switch (error.code) {
                            case error.PERMISSION_DENIED:
                                statusMessage.innerHTML =
                                    '<i class="bi bi-exclamation-triangle"></i> Izin akses lokasi ditolak. Menggunakan lokasi default.';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                statusMessage.innerHTML =
                                    '<i class="bi bi-exclamation-triangle"></i> Informasi lokasi tidak tersedia. Menggunakan lokasi default.';
                                break;
                            case error.TIMEOUT:
                                statusMessage.innerHTML =
                                    '<i class="bi bi-exclamation-triangle"></i> Waktu permintaan lokasi habis. Menggunakan lokasi default.';
                                break;
                            default:
                                statusMessage.innerHTML =
                                    '<i class="bi bi-exclamation-triangle"></i> Error tidak diketahui. Menggunakan lokasi default.';
                                break;
                        }
                    }, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 60000
                    }
                );
            } else {
                // Browser tidak mendukung geolocation
                const defaultLat = 1.095728;
                const defaultLng = 104.495919;
                updateLocation(defaultLat, defaultLng, 'default');

                const statusElement = document.getElementById('location-status');
                const statusMessage = document.getElementById('status-message');
                statusElement.className = 'location-status status-error';
                statusMessage.innerHTML =
                    '<i class="bi bi-exclamation-triangle"></i> Browser tidak mendukung geolocation. Menggunakan lokasi default.';
            }
        });

        function updateLocation(lat, lng, source) {
            if (!map) {
                initMap();
            }

            document.getElementById('create-koordinat').value = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;

            if (marker) {
                map.removeLayer(marker);
            }

            const myIcon = L.icon({
                iconUrl: '/assets/global/img/sign.png', // ganti sesuai path marker kamu
                iconSize: [22, 25],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
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
            }
        }

        // Event listener untuk input koordinat manual
        document.getElementById('create-koordinat').addEventListener('change', function() {
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

        document.addEventListener('DOMContentLoaded', function() {
            // Generate kode barang otomatis
            function generateKodeBarang() {
                const prefix = 'ASSET-';
                const timestamp = new Date().getTime().toString().substr(-5);
                const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
                return `${prefix}${timestamp}${random}`;
            }

            document.getElementById('create-kode_asset').value = generateKodeBarang();

            document.getElementById('generate-kode').addEventListener('click', function() {
                document.getElementById('create-kode_asset').value = generateKodeBarang();
            });
        });
    </script>
@endpush
