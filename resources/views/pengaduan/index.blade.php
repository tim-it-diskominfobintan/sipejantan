<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaduan Masyarakat</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        body {
            background: #f8f9fa;
        }

        #map {
            height: 400px;
            border-radius: 10px;
            border: 2px solid #ddd;
        }

        .form-section {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            margin-top: 20px;
        }

        .btn-custom {
            background: #0d6efd;
            color: #fff;
            border-radius: 8px;
            padding: 10px 18px;
            font-weight: 600;
        }

        .btn-custom:hover {
            background: #0b5ed7;
        }

        .font-small {
            font-size: 0.9rem;
        }
    </style>
</head>

<body>

    <div class="container py-4">
        <h2 class="text-center mb-4 fw-bold">Pengaduan Masyarakat</h2>
        <p class="text-center text-muted mb-3">Klik salah satu asset di peta untuk membuat laporan.</p>

        <div class="row">
            <div class="col-9">
                <div id="map"></div>
            </div>
            <div class="col-3">
                <div id="asset-detail" class="card d-none shadow-sm">
                    <div class="card-body row">
                        <!-- Kolom Detail -->
                        <div class="col-md-7">
                            <h5 class="card-title text-primary">Detail Asset</h5>
                            <ul class="list-unstyled font-small" id="asset-info">
                                <!-- detail asset akan di-render lewat JS -->
                            </ul>
                        </div>

                        <!-- Kolom Gambar -->
                        <div class="col-md-5 text-center">
                            <img id="asset-image" src="" alt="Foto Asset"
                                class="img-fluid rounded shadow-sm border"
                                style="max-height: 200px; object-fit: contain;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form class="form-section mt-4" method="POST">
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
                            <div class="col-md-3">
                                <label class="form-label">NIK *</label>
                                <input type="text" name="nik" class="form-control" id="create-nik"
                                    placeholder="Masukkan NIK">
                                <div id="create-nik-msg"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Nama Lengkap *</label>
                                <input type="text" name="nama" class="form-control" id="create-nama"
                                    placeholder="Masukkan Nama Lengkap">
                                <div id="create-nama-msg"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" class="form-control" id="create-email"
                                    placeholder="contoh@email.com">
                                <div id="create-email-msg"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">No Telp *</label>
                                <input type="text" name="no_hp" class="form-control" placeholder="0812 xxxx xxxx"
                                    id="create-no_hp">
                                <div id="create-no_hp-msg"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kecamatan *</label>
                                <select name="kecamatan_id" class="form-select" id="create-kecamatan_id">
                                    <option>Pilih Kecamatan</option>
                                    @foreach ($kecamatan as $item)
                                        <option value="{{ $item->id_kecamatan }}">{{ $item->nama_kecamatan }}</option>
                                    @endforeach
                                </select>
                                <div id="create-kecamatan_id-msg"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kelurahan *</label>
                                <select name="kelurahan_id" class="form-select" id="create-kelurahan_id">
                                    <option>Pilih Kelurahan</option>
                                    @foreach ($kelurahan as $item)
                                        <option value="{{ $item->id_kelurahan }}">{{ $item->nama_kelurahan }}</option>
                                    @endforeach
                                </select>
                                <div id="create-kelurahan_id-msg"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Alamat Lengkap *</label>
                                <textarea name="alamat" class="form-control" rows="1" id="create-alamat"></textarea>
                                <div id="create-alamat-msg"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Foto Identitas *</label>
                                <input type="file" name="foto_identitas" class="form-control" accept="image/*"
                                    id="create-foto_identitas">
                                <small class="text-muted">Format JPG/PNG, maksimal 2MB</small>
                                <div id="create-foto_identitas-msg"></div>
                            </div>
                        </div>
                        <hr class="my-3">
                        <!-- Detail Laporan -->
                        <h6 class="text-secondary mb-3">
                            <i class="bi bi-file-earmark-text"></i> Detail Laporan
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">No Laporan *</label>
                                <input type="text" name="no_laporan" class="form-control" readonly>
                                <small class="text-muted">Simpan No Laporan ini</small>
                            </div>
                            <div class="col-md-9">
                                <label class="form-label">Foto Laporan *</label>
                                <input type="file" name="foto_laporan" class="form-control" accept="image/*"
                                    id="create-foto_laporan">
                                <small class="text-muted">Format JPG/PNG, maksimal 2MB</small>
                                <div id="create-foto_laporan-msg"></div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Deskripsi Laporan *</label>
                                <textarea name="deskripsi_laporan" class="form-control" rows="3"
                                    placeholder="Jelaskan secara detail masalah atau kerusakan yang ditemukan" id="create-deskripsi_laporan"></textarea>
                                <div id="create-deskripsi_laporan-msg"></div>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-send"></i> Kirim Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </form>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
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

            var coords = asset.koordinat.split(',');
            var lat = parseFloat(coords[0].trim());
            var lng = parseFloat(coords[1].trim());

            // Tentukan warna sesuai status laporan
            let warna = '#ffffff';
            if (asset.laporan) {
                warna = warnaMap[asset.laporan.status_laporan] || '#ffffff';
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
                document.getElementById("asset_id").value = asset.id_asset;
                var detail = document.getElementById("asset-detail");
                detail.classList.remove("d-none");
                var infoHtml = `
                    <li><strong>Nama Asset :<br></strong> ${asset.nama_asset}</li>
                    <li class="mt-1"><strong>Kode Asset :<br></strong> ${asset.kode_asset}</li>
                    <li class="mt-1"><strong>Jenis Asset :<br></strong> ${asset.jenis_asset.jenis_asset}</li>
                    <li class="mt-1"><strong>Penanggung Jawab :<br></strong> ${asset.penanggung_jawab.nama_penanggung_jawab}</li>
                    <li class="mt-1"><strong>Jalan :<br></strong> ${asset.jalan.nama_jalan}</li>
                    <li class="mt-1"><strong>Koordinat :<br></strong> ${asset.koordinat}</li>
                    <li class="mt-1"><strong>Status Akhir :<br></strong> ${asset.laporan ? asset.laporan.status_laporan : '-'}</li>
                `;
                document.getElementById("asset-info").innerHTML = infoHtml;

                var imageUrl = asset.foto_asset ?
                    "{{ asset('storage') }}/" + asset.foto_asset :
                    'https://via.placeholder.com/300x200?text=No+Image';
                document.getElementById("asset-image").src = imageUrl;
            });
        });

        // === Legend ===
        var legend = L.control({
            position: 'bottomright'
        });
        legend.onAdd = function(map) {
            var div = L.DomUtil.create('div', 'info legend');
            div.style.background = 'white';
            div.style.padding = '10px';
            div.style.borderRadius = '8px';
            div.style.boxShadow = '0 2px 6px rgba(0,0,0,0.2)';
            div.style.fontSize = '13px';

            div.innerHTML = "<strong>Keterangan Status</strong><br>";

            for (var status in warnaMap) {
                div.innerHTML +=
                    '<i style="background:' + warnaMap[status] +
                    '; width:16px; height:16px; display:inline-block; margin-right:6px; border-radius:3px;"></i> ' +
                    status.charAt(0).toUpperCase() + status.slice(1) + '<br>';
            }
            return div;
        };
        legend.addTo(map);

        function generateNoLaporan(type = 'umum') {
            const now = new Date();
            const tahun = now.getFullYear();
            const bulan = String(now.getMonth() + 1).padStart(2, '0');
            const tanggal = String(now.getDate()).padStart(2, '0');
            const random = Math.floor(1000 + Math.random() * 9000); // 4 digit acak

            if (type === 'teknisi') {
                return `MAN-${tahun}${bulan}${tanggal}-${random}`;
            } else {
                return `LAP-${tahun}${bulan}${tanggal}-${random}`;
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            document.querySelector("input[name='no_laporan']").value = generateNoLaporan();
        });
    </script>
</body>

</html>
