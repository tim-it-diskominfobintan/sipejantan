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
    </style>
</head>

<body>

    <div class="container py-4">
        <h2 class="text-center mb-4 fw-bold">Pengaduan Masyarakat</h2>
        <p class="text-center text-muted mb-3">Klik salah satu asset di peta untuk membuat laporan.</p>

        <!-- Peta -->
        <div id="map"></div>

        <!-- Form -->
        <form class="form-section mt-4" method="POST" action="{{ route('laporan.store') }}"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="asset_id" id="asset_id">

            <h5 class="mb-3">Detail Laporan</h5>
            <div id="asset-detail" class="alert alert-info d-none"></div>

            <div class="mb-3">
                <label class="form-label">Judul Laporan <span class="text-danger">*</span></label>
                <input type="text" name="judul" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                <textarea name="deskripsi_laporan" class="form-control" rows="4" required></textarea>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Foto Bukti <span class="text-danger">*</span></label>
                    <input type="file" name="foto_laporan" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Kejadian <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_laporan" class="form-control" required>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-custom">Kirim Pengaduan</button>
            </div>
        </form>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // Inisialisasi peta
        var map = L.map('map').setView([-6.2, 106.816], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Ambil data asset dari backend
        var assets = @json($assets);
        // format di controller: $assets = Asset::all(['id','nama_asset','lat','lng','alamat']);

        assets.forEach(function(asset) {
            var marker = L.marker([asset.lat, asset.lng]).addTo(map);
            marker.bindPopup("<b>" + asset.nama_asset + "</b><br>" + asset.alamat);

            marker.on('click', function() {
                document.getElementById("asset_id").value = asset.id;
                var detail = document.getElementById("asset-detail");
                detail.classList.remove("d-none");
                detail.innerHTML = `
        <strong>Asset Dipilih:</strong> ${asset.nama_asset}<br>
        <small>${asset.alamat}</small>
      `;
            });
        });
    </script>
</body>

</html>
