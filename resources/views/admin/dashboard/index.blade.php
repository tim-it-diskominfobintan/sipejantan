@extends('layouts.admin.main')
@section('content')
    <style>
        @media (max-width: 991px) {
            #map {
                height: 350px !important;
            }
        }

        #detail-asset .card-body {
            padding: 15px;
        }

        #detail-asset h4 {
            font-size: 1.1rem;
            word-break: break-word;
        }

        #detail-asset ul li span {
            font-size: 0.9rem;
            word-break: break-word;
        }

        /* Thumbnail grid responsif */
        #detail-asset .foto-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
            gap: 8px;
            justify-items: center;
        }

        #detail-asset .foto-grid img {
            width: 100%;
            max-width: 110px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        #detail-asset .foto-grid img:hover {
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .btn.rounded-pill {
                font-size: 0.9rem;
                width: 100%;
            }
        }

        .legend-color {
            display: inline-block;
            border: 1px solid #e5e7eb;
        }

        .legend-profile:hover {
            transform: scale(1.1);
        }

        .legend-profile-default {
            border: 1px solid #d1d5db;
        }

        .custom-popup-container .leaflet-popup-content-wrapper {
            border-radius: 8px;
            padding: 0;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .custom-popup-container .leaflet-popup-content {
            margin: 0;
            width: 100% !important;
        }

        .popup-header {
            padding: 12px 15px;
            color: white;
            position: relative;
        }

        .popup-header h5 {
            margin: 0;
            font-weight: 600;
        }

        .popup-body {
            padding: 2px;
            background: white;
        }

        .popup-body p {
            margin-bottom: 3px;
            font-size: 14px;
        }

        .custom-marker-icon:hover {
            transform: scale(1.2);
            transition: transform 0.2s;
        }

        .leaflet-popup-tip {
            background: white;
        }

        .avatar {
            width: 35px;
            height: 35px;
            object-fit: contain;
        }
    </style>

    <div class="page-body">
        <div class="container-xl my-auto">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xxl-5 g-3 mb-3">
                @foreach ($kecamatan as $item)
                    <div class="col">
                        <div class="card shadow-sm border-0 rounded-4 h-100 hover-card">
                            <div class="card-body text-center p-3">
                                <h6 class="card-title mb-3 text-primary fw-bold">
                                    <i class="bi bi-geo-alt-fill me-1"></i>{{ $item->nama_kecamatan }}
                                </h6>

                                <div class="row text-center">
                                    <div class="col-6 border-end">
                                        <h5 class="fw-bold text-danger mb-1">
                                            {{ countLaporanByKecamatan($item->id_kecamatan, 'proses') ?? 0 }}
                                        </h5>
                                        <small class="text-danger">Proses</small>
                                    </div>
                                    <div class="col-6">
                                        <h5 class="fw-bold text-success mb-1">
                                            {{ countLaporanByKecamatan($item->id_kecamatan, 'selesai') ?? 0 }}
                                        </h5>
                                        <small class="text-success">Selesai</small>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="container-fluid">
                <div class="row g-3">
                    <!-- Kolom Peta -->
                    <div class="col-lg-8 col-md-12">
                        <div class="card shadow-sm h-100">
                            <div class="card-body p-2 p-md-3">
                                <div id="map" style="height: 500px; width: 100%; border-radius: 10px;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Detail Asset -->
                    <div class="col-lg-4 col-md-12">
                        <div class="card shadow-sm h-100" id="detail-asset">
                            <div class="card-body d-flex justify-content-center align-items-center text-muted">
                                <p class="mb-0">Pilih asset pada peta untuk melihat detail</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-body p-0 text-center">
                    <img id="lightboxImage" src="" class="img-fluid rounded shadow" alt="Zoomed Image">
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        var peta1 = L.tileLayer('https://mt1.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
            attribution: '© Diskominfo Bintan',
            maxZoom: 18,
        });

        var peta2 = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
            maxZoom: 18,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        });

        var peta3 = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        });

        var map = L.map('map', {
            center: [0.9712390563586268, 104.44936044954352],
            zoom: 10,
            layers: [peta3]
        });

        var baseLayers = {
            'Peta 1': peta1,
            'Peta 2': peta2,
            'Peta 3': peta3,
        };

        var layerControl = L.control.layers(baseLayers).addTo(map);

        var legend = L.control({
            position: 'bottomright'
        });

        function getAllAsset() {
            $.ajax({
                url: "{{ url()->current() }}" + "/asset",
                type: "get",
                dataType: "json",
                success: function(response) {
                    const data = response.data;
                    data.forEach(asset => {
                        if (!asset.koordinat) return;
                        let [lat, lng] = asset.koordinat.split(',').map(Number);
                        let warna = '#ffffff';
                        if (asset.laporan) {
                            const warnaMap = {
                                'pending': '#FF0000',
                                'diterima': '#0ca678',
                                'proses': '#024BA9'
                            };
                            warna = warnaMap[asset.laporan.status_laporan] || '#ffffff';
                        }

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

                        let marker = L.marker([lat, lng], {
                            icon: lampuIcon
                        });
                        map.addLayer(marker);

                        marker.on("click", function() {
                            renderDetailAsset(asset);
                        });

                        map.addLayer(marker);
                    });
                }
            });
        }

        function renderDetailAsset(asset) {
            let fotoHtml = '';

            if (asset.dokumen && asset.dokumen.length > 0) {
                let thumbnails = '';

                asset.dokumen.forEach((dok) => {
                    thumbnails += `
                        <div class="col-3 mb-2">
                            <img src="{{ asset('storage') }}/${dok.file_asset}" 
                                class="img-thumbnail cursor-pointer"
                                style="height:100px; width:100%; object-fit:cover;"
                                onclick="openLightbox('{{ asset('storage') }}/${dok.file_asset}')">
                        </div>
                    `;
                });

                fotoHtml = `
                    <!-- Thumbnail Grid -->
                    <div class="row g-2 justify-content-center">
                        ${thumbnails}
                    </div>
                `;
            } else {
                fotoHtml = `
                    <div class="col-3 mb-2">
                        <img src="{{ asset('assets/global/img/kardus.png') }}"
                            alt="Foto Asset"
                            class="img-thumbnail cursor-pointer"
                            style="height:100px; width:100%; object-fit:cover;"
                            onclick="openLightbox('{{ asset('assets/global/img/kardus.png') }}')">
                    </div>
                `;
            }

            $("#detail-asset").html(`
                <div class="card-body p-3">
                    <!-- Header -->
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center">
                            <div class="d-flex align-items-center justify-content-center" 
                                style="width:45px; height:45px;">
                                <img src="{{ asset('storage') }}/${asset.jenis_asset.foto_jenis_asset}" class="avatar">
                            </div>
                            <div class="ms-3">
                                <h4 class="mb-0 fw-bold">${ucwords(asset.nama_asset)}</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Info List -->
                    <ul class="list-unstyled mb-4">
                        <li class="d-flex align-items-center mb-2">
                            <i class="bi bi-diagram-3 text-primary me-2"></i>
                            <span>${ucwords(asset.jenis_asset.jenis_asset)}</span>
                        </li>
                        <li class="d-flex align-items-center mb-2">
                            <i class="bi bi-person-badge text-info me-2"></i>
                            <span>${ucwords(asset.penanggung_jawab.nama_penanggung_jawab)}</span>
                        </li>
                        <li class="d-flex align-items-center mb-2">
                            <i class="bi bi-signpost text-secondary me-2"></i>
                            <span>${ucwords(asset.jalan.nama_jalan)} - (${asset.jalan.panjang_jalan} M)</span>
                        </li>
                        <li class="d-flex align-items-center">
                            <i class="bi bi-geo-alt text-danger me-2"></i>
                            <span>${asset.koordinat}</span>
                        </li>
                    </ul>

                    <!-- Foto Asset -->
                    <div class="text-center mb-3">
                        ${fotoHtml}
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary rounded-pill" onclick="openLaporan(${asset.id_asset})">
                            <i class="bi bi-file-earmark-text me-1"></i> Laporan
                        </button>
                        <button class="btn btn-info rounded-pill text-white" onclick="openDetailAsset(${asset.id_asset})">
                            <i class="bi bi-list me-1"></i> Detail
                        </button>
                        <button class="btn btn-secondary rounded-pill" onclick="openRiwayat(${asset.id_asset})">
                            <i class="bi bi-clock-history me-1"></i> Riwayat
                        </button>
                    </div>
                </div>
            `);
        }

        function openLightbox(url) {
            $("#lightboxImage").attr("src", url);
            $('#lightboxModal').modal('show')
        }

        function openLaporan(assetId) {
            window.location.href = `laporan/create/${assetId}`;
        }

        function openRiwayat(assetId) {
            window.location.href = `laporan/${assetId}`;
        }

        function openDetailAsset(assetId) {
            window.location.href = `asset/${assetId}/detail`;
        }

        function ucwords(str) {
            return str.toLowerCase().replace(/\b\w/g, (c) => c.toUpperCase());
        }

        getAllAsset();
    </script>
@endpush
