@extends('layouts.out.main')

@section('title', $title)
@push('style')
    <style>
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

        /* Samakan tinggi & border select2 dengan input bootstrap */
        .select2-container .select2-selection--single {
            height: calc(2.25rem + 2px) !important;
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

        #asset-thumbnails {
            position: relative;
        }

        #asset-thumbnails::before {
            font-size: 14px;
            font-weight: 600;
            color: #444;
            margin-right: 10px;
        }

        #asset-thumbnails img {
            width: 45px;
            height: 45px;
            border-radius: 8px;
            border: 2px solid #ddd;
            /* 🔹 border abu-abu tipis */
            object-fit: cover;
            margin-left: -10px;
            cursor: pointer;
            background: #fff;
            /* biar jelas kalau fotonya transparan */
            transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
        }

        #asset-thumbnails img:first-child {
            margin-left: 0;
        }

        #asset-thumbnails img:hover {
            transform: scale(1.15);
            z-index: 2;
            border-color: #0d6efd;
            /* 🔹 berubah jadi biru saat hover */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        .asset-detail-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .asset-label {
            flex: 1;
            text-align: left;
            font-weight: bold;
            padding-right: 10px;
            white-space: nowrap;
        }

        .asset-value {
            flex: 1;
            text-align: right;
            white-space: pre-wrap;
            /* untuk nilai multiline */
        }

        .info.legend {
            background: white;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            font-family: Arial, sans-serif;
        }

        .info.legend h6 {
            margin: 0 0 8px 0;
            text-align: center;
            color: #333;
            font-weight: bold;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 6px;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            border: 2px solid #666;
            border-radius: 4px;
            margin-right: 8px;
            flex-shrink: 0;
        }

        .legend-label {
            color: #333;
            font-size: 12px;
            font-weight: normal;
        }

        /* Style untuk legend yang tersembunyi */
        .info.legend {
            z-index: 1000;
            border: 1px solid #ccc;
        }

        .info.legend:hover {
            opacity: 1 !important;
            transform: translateX(0) !important;
        }

        .legend-trigger:hover {
            background: #f8f9fa !important;
            color: #000 !important;
        }

        /* Untuk mobile responsiveness */
        @media (max-width: 768px) {
            .info.legend {
                max-width: 130px !important;
                font-size: 10px !important;
            }

            .legend-trigger {
                font-size: 10px !important;
                padding: 4px 8px !important;
            }
        }
    </style>
@endpush
@section('content')
    <div class="container-xl">
        <div class="container py-4">
            <h2 class="text-center mb-1 fw-bold">Pengaduan Masyarakat</h2>
            <p class="text-center text-muted mb-3">Klik salah satu asset di peta untuk membuat laporan.</p>

            <!-- Peta -->
            <div class="row">
                <div class="col-md-9 col-sm-12">
                    <div id="map"
                        style="position: relative; width: 100%; height: 480px; border-radius: 12px; box-shadow: 0px 0px 5px 1px rgba(0,0,0,0.1);">
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <div class="card shadow-sm">
                        <div class="d-flex align-items-center border-bottom p-2">
                            <!-- Tombol kamera -->
                            <button id="btnScanAsset" class="btn btn-outline-secondary me-2" title="Scan Barcode">
                                <i class="bi bi-camera"></i>
                            </button>
                            <!-- Input cari asset -->
                            <input type="text" class="form-control border-0" id="search-asset"
                                placeholder="Cari Asset (nama / kode)..." autocomplete="off">
                        </div>
                        <div class="card-body row d-none" id="asset-detail">
                            <!-- Kolom Detail -->
                            <h6 class="text-muted mb-2">Foto / Dokumen</h6>
                            <div id="asset-thumbnails" class="d-flex flex-wrap mb-3">
                                <!-- thumbnail akan diisi lewat JS -->
                            </div>

                            <div class="col-md-12 col-sm-12">
                                <h6 class="card-title text-primary">Detail Asset</h6>
                                <ul class="list-unstyled font-small" id="asset-info">
                                    <!-- detail asset akan di-render lewat JS -->
                                </ul>
                            </div>

                            <!-- Kolom Gambar -->
                            {{-- <div class="col-md-5 text-center">
                                <img id="asset-image" src="" alt="Foto Asset"
                                    class="img-fluid rounded shadow-sm border"
                                    style="max-height: 200px; object-fit: contain;">
                            </div> --}}

                            <div class="modal fade" id="assetModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-body text-center" id="asset-modal-body">
                                            <!-- gambar tunggal akan dirender lewat JS -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="cameraModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            <div id="readerAsset" style="width:100%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section mt-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="text-primary mb-3">
                            <i class="bi bi-clipboard-plus"></i> Form Pengajuan Laporan
                        </h5>

                        <form id="form-laporan">
                            <!-- Data Pelapor -->
                            <h6 class="text-secondary mb-3">
                                <i class="bi bi-person-circle"></i> Data Pelapor
                            </h6>
                            <input type="hidden" name="asset_id" id="asset_id">
                            <div class="row g-3">
                                <div class="col-md-9 col-sm-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">NIK *</label>
                                            <input type="text" name="nik" class="form-control" id="create-nik"
                                                placeholder="Masukkan NIK">
                                            <div id="create-nik-msg"></div>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label">Nama Lengkap *</label>
                                            <input type="text" name="nama" class="form-control" id="create-nama"
                                                placeholder="Masukkan Nama Lengkap">
                                            <div id="create-nama-msg"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Email *</label>
                                            <input type="email" name="email" class="form-control" id="create-email"
                                                placeholder="contoh@email.com">
                                            <div id="create-email-msg"></div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="form-label">No Telp *</label>
                                            <input type="text" name="no_hp" class="form-control"
                                                placeholder="0812 xxxx xxxx" id="create-no_hp">
                                            <div id="create-no_hp-msg"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Kecamatan *</label>
                                            <select name="kecamatan_id" class="form-select" id="create-kecamatan_id">
                                                <option value="">Pilih Kecamatan</option>
                                                @foreach ($kecamatan as $item)
                                                    <option value="{{ $item->id_kecamatan }}">{{ $item->nama_kecamatan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div id="create-kecamatan_id-msg"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Kelurahan *</label>
                                            <select name="kelurahan_id" class="form-select" id="create-kelurahan_id"
                                                disabled>
                                                <option value="">Pilih Kelurahan</option>
                                            </select>
                                            <div id="create-kelurahan_id-msg"></div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <label class="form-label">Alamat Lengkap *</label>
                                            <textarea name="alamat" class="form-control" rows="1" id="create-alamat"></textarea>
                                            <div id="create-alamat-msg"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="col-md-12">
                                        <label class="form-label">Foto Identitas * </label>
                                        <div class="upload-box" id="uploadBox">
                                            <div id="uploadText">
                                                <div class="upload-icon">☁️</div>
                                                <strong>Unggah disini</strong> <br><small class="text-muted">Format
                                                    JPG/JPEG/PNG, maksimal 2MB</small>
                                            </div>
                                            <input type="file" name="foto_identitas" accept="image/*"
                                                class="hidden-input" id="fotoInput">
                                            <div id="preview"></div>
                                            <div id="create-foto_identitas-msg"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-3">
                            <!-- Detail Laporan -->
                            <h6 class="text-secondary mb-3">
                                <i class="bi bi-file-earmark-text"></i> Detail Laporan
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-7 col-sm-12">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="form-label">No Laporan *</label>
                                            <input type="text" name="no_laporan" class="form-control" readonly>
                                            <small class="text-muted">Simpan No Laporan ini</small>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Nama Asset *</label>
                                            <input type="text" name="nama_asset" id="create-nama_asset"
                                                class="form-control" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Tanggal Laporan *</label>
                                            <input type="text" class="form-control" value="{{ date('d F Y') }}"
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-2">
                                        <label class="form-label">Deskripsi Laporan *</label>
                                        <textarea name="deskripsi_laporan" class="form-control" rows="3"
                                            placeholder="Jelaskan secara detail masalah atau kerusakan yang ditemukan" id="create-deskripsi_laporan"></textarea>
                                        <div id="create-deskripsi_laporan-msg"></div>
                                    </div>
                                </div>
                                <div class="col-md-5 col-sm-12">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Foto Laporan *
                                                <small class="text-muted">Format JPG/PNG, maks 2MB</small>
                                            </label>
                                            <input type="file" class="form-control" id="create-foto_laporan"
                                                name="foto_laporan[]" accept="image/*" multiple>
                                        </div>
                                        <!-- Tempat preview -->
                                        <div id="preview-foto_laporan" class="row g-2"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 text-md-end text-center">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-send me-2"></i> Kirim Pengaduan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>

    <script>
        $('#create-kecamatan_id').select2({
            placeholder: "Pilih Kecamatan",
            width: '100%'
        });

        $('#create-kelurahan_id').select2({
            placeholder: "Pilih Kelurahan",
            width: '100%'
        });

        // ketika kecamatan berubah
        $('#create-kecamatan_id').on('change', function() {
            let kecamatanId = $(this).val();

            // reset kelurahan
            $('#create-kelurahan_id').empty().append('<option value="">Pilih Kelurahan</option>').prop('disabled',
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

        // Inisialisasi peta
        var map = L.map('map').setView([0.9777319006966241, 104.46584971129931], 10);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Ambil data asset dari backend
        var assets = @json($assets);

        const warnaMap = {
            'pending': '#FF0000',
            'diterima': '#0ca678',
            'proses': '#024BA9'
        };

        assets.forEach(function(asset) {
            if (!asset.koordinat) return; // skip kalau tidak ada koordinat
            console.log(asset);
            var coords = asset.koordinat.split(',');
            var lat = parseFloat(coords[0].trim());
            var lng = parseFloat(coords[1].trim());

            // Tentukan warna sesuai status laporan
            let warna = '#ffffff'; // default abu-abu
            if (asset.latest_laporan && asset.latest_laporan.status_laporan) {
                warna = warnaMap[asset.latest_laporan.status_laporan.toLowerCase()] || '#ffffff';
            }

            // Icon asset
            let iconUrl = "{{ asset('storage') }}/" + asset.jenis_asset.foto_jenis_asset;
            let lampuIcon = L.divIcon({
                className: "custom-lampu-icon",
                html: `
            <div style="position: relative; display: inline-block;">
                <!-- Kotak utama -->
                <div style="
                    background-color: ${warna}; 
                    border-radius: 8px; 
                    padding: 6px; 
                    width: 30px; 
                    height: 30px; 
                    display: flex; 
                    align-items: center; 
                    justify-content: center;
                    box-shadow: 0 2px 6px rgba(0,0,0,0.3);
                    border: 3px solid ${asset.penanggung_jawab.warna_penanggung_jawab};
                ">
                    <img src="${iconUrl}" 
                        alt="Lampu Jalan" 
                        style="width:24px; height:24px; object-fit:contain;">
                </div>

                <!-- Segitiga di bawah kotak -->
                <div style="
                    position: absolute;
                    bottom: -10px; 
                    left: 50%; 
                    transform: translateX(-50%);
                    width: 0; 
                    height: 0; 
                    border-left: 8px solid transparent;
                    border-right: 8px solid transparent;
                    border-top: 10px solid ${warna};
                "></div>
            </div>
        `,
                iconSize: [40, 50],
                iconAnchor: [20, 50],
                popupAnchor: [0, -40]
            });

            // Tambahkan marker
            var marker = L.marker([lat, lng], {
                icon: lampuIcon
            }).addTo(map);

            marker.bindPopup("<b>" + asset.nama_asset + "</b><br>" + asset.jalan.nama_jalan);

            marker.on('click', function() {
                tampilkanDetailAsset(asset);
            });
        });

        function tampilkanDetailAsset(asset) {
            const detail = document.getElementById("asset-detail");
            detail.classList.remove("d-none");
            const infoHtml = `
        <div class="asset-detail-item mb-2"><b>Nama Asset:</b> ${asset.nama_asset}</div>
        <div class="asset-detail-item mb-2"><b>Kode Asset:</b> ${asset.kode_asset}</div>
        <div class="asset-detail-item mb-2"><b>Jenis Asset:</b> ${asset.jenis_asset.jenis_asset}</div>
        <div class="asset-detail-item mb-2"><b>Penanggung Jawab:</b> ${asset.penanggung_jawab.nama_penanggung_jawab}</div>
        <div class="asset-detail-item mb-2"><b>Jalan:</b> ${asset.jalan.nama_jalan}</div>
        <div class="asset-detail-item mb-2"><b>Koordinat:</b> ${asset.koordinat}</div>
        <div class="asset-detail-item mb-2"><b>Status Akhir:</b> ${asset.latest_laporan && asset.latest_laporan.status_laporan ? asset.latest_laporan.status_laporan : '-'}</div>
        <div class="asset-detail-item mb-2"><b>Kondisi:</b> ${asset.kondisi}</div>
    `;
            document.getElementById("asset-info").innerHTML = infoHtml;
            $("#create-nama_asset").val(asset.nama_asset);
            $("#asset_id").val(asset.id_asset);

            const thumbContainer = document.getElementById("asset-thumbnails");
            thumbContainer.innerHTML = "";
            if (asset.dokumen && asset.dokumen.length > 0) {
                asset.dokumen.forEach((dok, idx) => {
                    let img = document.createElement("img");
                    img.src = "{{ asset('storage') }}/" + dok.file_asset;
                    img.classList.add("me-2", "mb-2", "rounded", "shadow-sm");
                    img.style.width = "55px";
                    img.style.height = "55px";
                    img.style.objectFit = "cover";
                    img.style.cursor = "pointer";

                    img.addEventListener("click", function() {
                        document.getElementById("asset-modal-body").innerHTML = `
                    <img src="{{ asset('storage') }}/${dok.file_asset}" 
                        class="img-fluid rounded shadow-sm" 
                        style="max-height:400px;">
                `;
                        new bootstrap.Modal(document.getElementById("assetModal")).show();
                    });
                    thumbContainer.appendChild(img);
                });
            }

            if (asset.koordinat) {
                let coords = asset.koordinat.split(',').map(Number);
                map.setView(coords, 16);
                L.popup({
                        offset: L.point(-5, -40) // nilai negatif = naik ke atas, sesuaikan misalnya -25 atau -30
                    })
                    .setLatLng(coords)
                    .setContent("<b>" + asset.nama_asset + "</b>")
                    .openOn(map);
            }
        }

        $(function() {
            const assetList = assets.map(a => ({
                label: a.nama_asset + " (" + a.kode_asset + ")",
                value: a.kode_asset
            }));

            $("#search-asset").autocomplete({
                source: assetList,
                select: function(event, ui) {
                    const keyword = ui.item.value.toLowerCase();
                    const found = assets.find(a =>
                        a.nama_asset.toLowerCase().includes(keyword) ||
                        a.kode_asset.toLowerCase().includes(keyword)
                    );
                    if (found) tampilkanDetailAsset(found);
                }
            });
        });

        let html5QrCodeAsset = null;

        document.getElementById("btnScanAsset").addEventListener("click", function() {
            const modal = new bootstrap.Modal(document.getElementById("cameraModal"));
            modal.show();

            // Tunggu modal muncul agar div #readerAsset tersedia di DOM
            setTimeout(() => {
                // Cegah error multiple instance
                if (html5QrCodeAsset) {
                    html5QrCodeAsset.stop().catch(() => {}).then(() => html5QrCodeAsset.clear());
                }

                html5QrCodeAsset = new Html5Qrcode("readerAsset");

                html5QrCodeAsset.start({
                        facingMode: "environment"
                    }, {
                        fps: 10,
                        qrbox: {
                            width: 250,
                            height: 250
                        }
                    },
                    (decodedText) => {
                        // Hentikan kamera setelah berhasil
                        html5QrCodeAsset.stop().then(() => {
                            modal.hide();

                            // Cari asset berdasarkan hasil scan
                            const found = assets.find(a =>
                                a.nama_asset.toLowerCase().includes(decodedText
                                    .toLowerCase()) ||
                                a.kode_asset.toLowerCase().includes(decodedText
                                    .toLowerCase())
                            );

                            if (found) {
                                tampilkanDetailAsset(found);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Asset ditemukan',
                                    text: `${found.nama_asset} (${found.kode_asset})`,
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Asset tidak ditemukan',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            }
                        });
                    },
                    (errorMsg) => {
                        // abaikan error kecil (misalnya gagal fokus)
                        console.log("Scan error:", errorMsg);
                    }
                ).catch((err) => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kamera gagal dijalankan',
                        text: err,
                    });
                });
            }, 400);
        });

        // Matikan kamera jika modal ditutup manual
        document.getElementById("cameraModal").addEventListener("hidden.bs.modal", function() {
            if (html5QrCodeAsset) {
                html5QrCodeAsset.stop().catch(() => {}).then(() => html5QrCodeAsset.clear());
            }
        });

        var legend = L.control({
            position: 'bottomright'
        });

        legend.onAdd = function(map) {
            var div = L.DomUtil.create('div', 'info legend');

            div.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
            div.style.padding = '10px';
            div.style.borderRadius = '8px';
            div.style.boxShadow = '0 2px 10px rgba(0,0,0,0.3)';
            div.style.maxWidth = '160px';
            div.style.cursor = 'pointer';

            // Hanya icon awalnya
            div.innerHTML = `
        <div style="text-align: center; font-size: 16px;">📊</div>
        <div style="text-align: center; font-size: 9px; color: #666; margin-top: 2px;">Klik untuk info</div>
        
        <div id="legend-content" style="display: none; margin-top: 8px; border-top: 1px solid #eee; padding-top: 8px;">
            <div style="text-align: center; margin-bottom: 8px; color: #333; font-size: 11px;">
                <strong>STATUS LAPORAN</strong>
            </div>
            <div style="display: flex; align-items: center; margin-bottom: 5px;">
                <div style="width: 12px; height: 12px; background: #FF0000; border: 2px solid #cc0000; border-radius: 3px; margin-right: 6px;"></div>
                <span style="color: #555; font-size: 11px;">Pending</span>
            </div>
            <div style="display: flex; align-items: center; margin-bottom: 5px;">
                <div style="width: 12px; height: 12px; background: #0ca678; border: 2px solid #099268; border-radius: 3px; margin-right: 6px;"></div>
                <span style="color: #555; font-size: 11px;">Diterima</span>
            </div>
            <div style="display: flex; align-items: center; margin-bottom: 5px;">
                <div style="width: 12px; height: 12px; background: #024BA9; border: 2px solid #013a7f; border-radius: 3px; margin-right: 6px;"></div>
                <span style="color: #555; font-size: 11px;">Proses</span>
            </div>
            <div style="display: flex; align-items: center;">
                <div style="width: 12px; height: 12px; background: #ffffff; border: 2px solid #999; border-radius: 3px; margin-right: 6px;"></div>
                <span style="color: #555; font-size: 11px;">Tidak Ada Laporan</span>
            </div>
        </div>
    `;

            // Toggle legend content on click
            div.addEventListener('click', function() {
                var content = document.getElementById('legend-content');
                if (content.style.display === 'none') {
                    content.style.display = 'block';
                    div.style.minWidth = '160px';
                } else {
                    content.style.display = 'none';
                    div.style.minWidth = 'auto';
                }
            });

            return div;
        };

        legend.addTo(map);

        function generateNoLaporan(type = 'umum') {
            const now = new Date();
            const tahun = now.getFullYear();
            const bulan = String(now.getMonth() + 1).padStart(2, '0');
            const tanggal = String(now.getDate()).padStart(2, '0');
            const random = Math.floor(1000 + Math.random() * 9000); // 4 digit acak

            return `LAP-${tahun}${bulan}${tanggal}-${random}`;
        }

        document.addEventListener("DOMContentLoaded", function() {
            document.querySelector("input[name='no_laporan']").value = generateNoLaporan();
        });

        const uploadBox = document.getElementById("uploadBox");
        const fotoInput = document.getElementById("fotoInput");
        const preview = document.getElementById("preview");
        const uploadText = document.getElementById("uploadText");

        // klik upload
        uploadBox.addEventListener("click", () => fotoInput.click());

        // drag over
        uploadBox.addEventListener("dragover", (e) => {
            e.preventDefault();
            uploadBox.classList.add("dragover");
        });

        uploadBox.addEventListener("dragleave", () => {
            uploadBox.classList.remove("dragover");
        });

        // drop file
        uploadBox.addEventListener("drop", (e) => {
            e.preventDefault();
            uploadBox.classList.remove("dragover");
            const file = e.dataTransfer.files[0];
            if (file) {
                fotoInput.files = e.dataTransfer.files;
                showPreview(file);
            }
        });

        // pilih lewat input
        fotoInput.addEventListener("change", () => {
            const file = fotoInput.files[0];
            if (file) {
                showPreview(file);
            }
        });

        function showPreview(file) {
            if (!file.type.startsWith("image/")) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                uploadText.style.display = "none";
            };
            reader.readAsDataURL(file);
        }

        const fotoLaporanInput = document.getElementById("create-foto_laporan");
        const previewContainer = document.getElementById("preview-foto_laporan");

        fotoLaporanInput.addEventListener("change", () => {
            previewContainer.innerHTML = ""; // reset preview setiap kali input berubah

            Array.from(fotoLaporanInput.files).forEach((file, index) => {
                if (!file.type.startsWith("image/")) return;

                const reader = new FileReader();
                reader.onload = (e) => {
                    const col = document.createElement("div");
                    col.classList.add("col-3", "position-relative");

                    col.innerHTML = `
                <div class="border rounded p-1 shadow-sm">
                    <img src="${e.target.result}" 
                        class="img-fluid rounded" 
                        style="max-height:120px; object-fit:cover;">
                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 btn-remove"
                            data-index="${index}">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            `;
                    previewContainer.appendChild(col);
                };
                reader.readAsDataURL(file);
            });
        });

        // HAPUS FOTO DARI PREVIEW
        $(document).on("click", ".btn-remove", function() {
            let index = $(this).data("index");

            // buat FileList baru tanpa file yang dihapus
            let dt = new DataTransfer();
            let files = fotoLaporanInput.files;
            for (let i = 0; i < files.length; i++) {
                if (i !== index) {
                    dt.items.add(files[i]);
                }
            }
            fotoLaporanInput.files = dt.files;

            // refresh preview
            $(this).closest(".col-3").remove();
        });

        $('#form-laporan').submit(function(e) {
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
                url: "{{ url('pengaduan') }}",
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
                    // console.log(response.data.laporan.no_laporan);
                    $(`#form-${formMode}`).trigger('reset')
                    $(`#modal-${formMode}`).modal('hide')
                    showSwal(response.message, response.status)
                    // window.location.href = "/informasi_tiket";
                    window.location.href = "/pengaduan/success/" + response.data.laporan.no_laporan;
                },
                error: function(response) {
                    console.log(response)
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

        function capitalizeEachWord(str) {
            return str.replace(/\b\w/g, char => char.toUpperCase());
        }
    </script>
@endpush
