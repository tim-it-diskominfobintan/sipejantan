@extends('layouts.admin.main')

@section('action-header')
    <style>
        .map-container {
            height: 350px;
            width: 100%;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 1rem;
            z-index: 1;
        }

        .detail-item {
            padding: 0.8rem 1rem;
            border-radius: 8px;
            background-color: #f8f9fa;
            margin-bottom: 0.8rem;
            border-left: 4px solid #4285f4;
        }

        .detail-item h6 {
            color: #5f6368;
            font-size: 0.85rem;
            margin-bottom: 0.3rem;
            font-weight: 500;
        }

        .detail-item p {
            color: #202124;
            font-weight: 500;
            margin-bottom: 0;
        }

        .road-icon {
            background-color: #e8f0fe;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: #1a73e8;
            font-size: 1.5rem;
        }

        .header-content {
            display: flex;
            align-items: center;
        }

        .header-text {
            flex: 1;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .legend {
            position: absolute;
            bottom: 15px;
            right: 15px;
            background: white;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            font-size: 0.8rem;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }

        .legend-color {
            width: 20px;
            height: 10px;
            margin-right: 8px;
            border-radius: 2px;
        }

        .instructions {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .instructions h5 {
            color: #1a73e8;
            margin-bottom: 10px;
        }

        .instructions ol {
            padding-left: 20px;
        }

        .instructions li {
            margin-bottom: 8px;
        }
    </style>
    {{-- UNTUK TOMBOL TAMBAH DATA DLL, KALO GAADA KOSONGIN AJA --}}
    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            <a href="{{ url('admin/master/jalan/create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i> Tambah {{ $title }}
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">List {{ $title }}</h3>
                    {{-- <button id="reload" class="ms-5">Reload</button> --}}
                </div>
                <div class="card-body py-0 px-0 mx-0">
                    <div class="table-responsive table-full-to-card-body">
                        <table class="table" id="table">
                            <thead>
                                <tr class="bg-body-tertiary">
                                    <th width="20px">No</th>
                                    <th>Nama Jalan</th>
                                    <th>Panjang Jalan (M)</th>
                                    <th>Penanggung Jawab</th>
                                    <th>Kecamatan</th>
                                    <th>Kelurahan</th>
                                    <th>Peta</th>
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

    @include('admin.master.jalan.modal')
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
                    data: 'nama_jalan',
                    name: 'nama_jalan'
                },
                {
                    data: 'panjang_jalan',
                    name: 'panjang_jalan',
                    className: 'text-left'
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
                    data: 'lihat_map',
                    name: 'lihat_map'
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

        let map = null;
        let geojsonLayer = null;

        $('#table tbody').on('click', '.btn-detail', function() {
            let detail = $(this).data('detail');
            $('#modal-detailLabel').text('Detail ' + detail.nama_jalan);
            $('#detail-panjang').text(detail.panjang_jalan + ' km');
            $('#detail-penanggung_jawab_id').text(detail.penanggung_jawab.nama_penanggung_jawab);
            $('#detail-kelurahan').text(detail.kelurahan.nama_kelurahan);
            $('#detail-kecamatan').text(detail.kecamatan.nama_kecamatan);

            $('#modal-detail').modal('show');

            setTimeout(function() {
                initializeMap(detail);
            }, 300);
        })

        $('#modal-detail').on('hidden.bs.modal', function() {
            // Hapus peta saat modal ditutup
            if (map) {
                map.remove();
                map = null;
                geojsonLayer = null;
                detail = null;
            }
        });

        function initializeMap(detail) {
            // Hapus peta sebelumnya jika ada
            if (map) {
                map.remove();
            }

            // Inisialisasi peta
            map = L.map('map').setView([1.065, 104.225], 12);

            // Tambahkan tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Pastikan geojson_jalan adalah objek, bukan string
            const geojsonData = typeof detail.geojson_jalan === 'string' ?
                JSON.parse(detail.geojson_jalan) :
                detail.geojson_jalan;

            const lineFeatures = geojsonData.features.filter(
                feature => feature.geometry.type === "LineString"
            );

            const lineGeoJSON = {
                type: "FeatureCollection",
                features: lineFeatures
            };

            geojsonLayer = L.geoJSON(lineGeoJSON, {
                style: function(feature) {
                    return {
                        color: "#1a73e8",
                        weight: 5,
                        opacity: 0.8
                    };
                },
            }).addTo(map);

            // Sesuaikan tampilan peta agar seluruh jalan terlihat
            if (geojsonLayer.getBounds().isValid()) {
                map.fitBounds(geojsonLayer.getBounds().pad(0.1));
            }

            // Pastikan peta di-render dengan benar setelah modal terbuka
            setTimeout(function() {
                map.invalidateSize();
            }, 350);
        }

        $('#table tbody').on('click', '.btn-delete', function() {
            const id = $(this).data('id')

            showSwalConfirm('Hapus', 'Ingin mengahpus data?', 'warning', function(
                result) {
                if (result) {
                    const formData = new FormData()

                    formData.append('_method', 'delete')
                    formData.append('_token', getCsrfToken())
                    formData.append('id', id)

                    callApi(formData, `{{ url()->current() }}/${id}`)
                }
            })
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

                    table.ajax.reload()
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
    </script>
@endpush
