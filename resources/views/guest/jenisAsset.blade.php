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

        .custom-cluster {
            background: transparent;
            border-radius: 50%;
            position: relative;
        }

        .cluster-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            position: relative;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .cluster-icon span {
            position: relative;
            z-index: 2;
        }

        .cluster-icon .pulse {
            position: absolute;
            top: -10px;
            left: -10px;
            width: 60px;
            height: 60px;
            border: 4px solid rgba(255, 0, 0, 0.4);
            border-radius: 50%;
            animation: pulseAnim 2s infinite;
            z-index: 1;
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
    </style>
@endpush
@section('content')
    <div class="container-xl">
        <!-- Bagian Atas dengan Judul dan Deskripsi -->
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card-body">
                    <!-- Main Content -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div id="map"
                                style="position: relative; width: 100%; height: 480px; border-radius: 12px; box-shadow: 0px 0px 5px 1px rgba(0,0,0,0.1);">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-4">
                <div class="card p-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">{{ $title }}</h3>
                        {{-- <button id="reload" class="ms-5">Reload</button> --}}
                        <button id="resetTable" class="btn btn-secondary btn-md mt-2">Tampilkan Semua</button>
                    </div>
                    <div class="card-body py-1 px-0 mx-0">
                        <div class="table-responsive table-full-to-card-body">
                            <table class="table" id="table">
                                <thead>
                                    <tr class="bg-body-tertiary">
                                        <th width="20px">No</th>
                                        <th>Nama Asset</th>
                                        <th>Jenis Asset</th>
                                        <th>Jalan</th>
                                        <th>Penanggung Jawab</th>
                                        <th>Kecamatan</th>
                                        <th>Kelurahan</th>
                                        <th>Kondisi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        let formMode = 'create'

        const table = $('#table').DataTable({
            serverSide: true,
            columnDefs: [{
                targets: [0, -1],
                className: "text-center",
                responsivePriority: 1,
                width: '10%'
            }],
            ajax: {
                url: "{{ url()->current() }}",
                method: 'GET',
                beforeSend: function() {
                    renderRowSpinner();
                },
                error: function(response) {
                    handleAjaxJqueryError(response)
                }
            },
            lengthMenu: datatablesDefaultConfig().lengthMenu,
            pageLength: datatablesDefaultConfig().pageLength,
            language: datatablesDefaultConfig().language,
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'nama_asset',
                    name: 'nama_asset'
                },
                {
                    data: 'jenis_asset',
                    name: 'jenis_asset'
                },
                {
                    data: 'nama_jalan',
                    name: 'nama_jalan'
                },
                {
                    data: 'nama_penanggung_jawab',
                    name: 'nama_penanggung_jawab'
                },
                {
                    data: 'nama_kecamatan',
                    name: 'nama_kecamatan'
                },
                {
                    data: 'nama_kelurahan',
                    name: 'nama_kelurahan'
                },
                {
                    data: 'kondisi',
                    name: 'kondisi'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $('#reload').click(function() {
            table.ajax.reload()
        })

        // === Base Layer ===
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

        // === Cluster Group dengan custom icon ===
        var markers = L.markerClusterGroup({
            iconCreateFunction: function(cluster) {
                var childCount = cluster.getChildCount();

                let warna = "rgba(255,0,0,0.8)"; // default merah
                if (childCount < 20) warna = "rgba(0,150,0,0.8)"; // hijau
                else if (childCount < 50) warna = "rgba(255,165,0,0.8)"; // oranye

                return L.divIcon({
                    html: `
                        <div class="cluster-icon" style="background:${warna}">
                            <div class="pulse" style="border-color:${warna.replace('0.8','0.4')}"></div>
                            <span>${childCount}</span>
                        </div>
                    `,
                    className: 'custom-cluster',
                    iconSize: [40, 40]
                });
            }
        });

        // === Warna status laporan ===
        const warnaMap = {
            'pending': '#FF0000',
            'diterima': '#0ca678',
            'proses': '#024BA9'
        };

        var assets = @json($assets);

        // === Tambahkan marker ke cluster ===
        assets.forEach(function(asset) {
            if (!asset.koordinat) return;

            var coords = asset.koordinat.split(',');
            var lat = parseFloat(coords[0].trim());
            var lng = parseFloat(coords[1].trim());

            let warna = '#ffffff';
            if (asset.latest_laporan && asset.latest_laporan.status_laporan) {
                warna = warnaMap[asset.latest_laporan.status_laporan.toLowerCase()] || '#ffffff';
            }

            let iconUrl = "{{ asset('storage') }}/" + asset.jenis_asset.foto_jenis_asset;

            let customIcon = L.divIcon({
                className: "custom-lampu-icon",
                html: `
                        <div style="position: relative; display: inline-block;">
                            <div style="
                                background-color: ${warna};
                                border: 2px solid #333;
                                border-radius: 8px;
                                padding: 6px;
                                width: 34px;
                                height: 34px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                box-shadow: 0 2px 6px rgba(0,0,0,0.3);
                                border: 3px solid ${asset.penanggung_jawab.warna_penanggung_jawab};
                            ">
                                <img src="${iconUrl}" 
                                    alt="${asset.nama_asset}" 
                                    style="width:24px; height:24px; object-fit:contain;">
                            </div>
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

            var marker = L.marker([lat, lng], {
                icon: customIcon
            });

            marker.bindPopup(`
                <div style="min-width:200px">
                    <strong>${asset.nama_asset}</strong><br>
                    Kode: ${asset.kode_asset}<br>
                    <small>${asset.jalan ? asset.jalan.nama_jalan : '-'}</small><br>
                    <small>Status: ${asset.latest_laporan ? (asset.latest_laporan.status_laporan === 'ditolak' ? 'baik' : asset.latest_laporan.status_laporan): 'baik'}</small><br>
                    <small>Kondisi: ${asset.kondisi}</small><br>
                    <small>Penanggung Jawab: ${asset.penanggung_jawab.nama_penanggung_jawab}</small><br>
                </div>
            `);

            marker.on('click', function() {
                table.column(1)
                    .search(asset.nama_asset)
                    .draw();
            });

            markers.addLayer(marker);
        });

        // === Tambahkan cluster ke map ===
        map.addLayer(markers);

        $('#resetTable').on('click', function() {
            table.search('').columns().search('').draw();
        });
    </script>
@endpush
