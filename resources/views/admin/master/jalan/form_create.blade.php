@extends('layouts.admin.main')
@section('content')
    <style>
        #map {
            height: 500px;
            width: 100%;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            border: none;
            margin-bottom: 20px;
        }

        .card-header {
            background: linear-gradient(120deg, #1a73e8, #4285f4);
            color: white;
            border-bottom: none;
            padding: 1.2rem 1.5rem;
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

        /* Samakan tinggi & border select2 dengan input bootstrap */
        .select2-container .select2-selection--single {
            height: calc(2.1rem + 2px) !important;
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
                <div class="instructions">
                    <h5><i class="bi bi-info-circle me-1"></i>Petunjuk Penggunaan</h5>
                    <ol>
                        <li>Gunakan toolbar draw (pensil) di pojok kanan atas peta untuk menggambar garis jalan</li>
                        <li>Setelah selesai menggambar, GeoJSON akan otomatis tersimpan di textarea bawah</li>
                        <li>Gunakan toolbar edit (pensil dengan garis putus) untuk mengedit garis yang sudah dibuat</li>
                        <li>Gunakan toolbar delete (tong sampah) untuk menghapus garis</li>
                    </ol>
                </div>

                <div id="map"></div>

                <form id="form-create">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="create-nama_jalan" class="form-label fw-semibold"><i
                                        class="bi bi-signpost-fill text-primary me-1"></i>Nama Jalan</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-signpost-fill"></i></span>
                                    <input type="text" id="create-nama_jalan" name="nama_jalan" class="form-control"
                                        placeholder="Masukkan Nama Jalan">
                                </div>
                                <div id="create-nama_jalan-msg"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="create-panjang_jalan" class="form-label fw-semibold"><i
                                        class="bi bi-rulers text-primary me-1"></i>Panjang Jalan (M)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-rulers"></i></span>
                                    <input type="number" id="create-panjang_jalan" name="panjang_jalan"
                                        class="form-control" placeholder="Masukkan Panjang">
                                </div>
                                <div id="create-panjang_jalan-msg"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="create-kecamatan_id" class="form-label fw-semibold"><i
                                    class="bi bi-building text-success me-1"></i>Nama Kecamatan</label>
                            <select name="kecamatan_id" id="create-kecamatan_id" class="form-select select2">
                                <option value="" disabled selected>Pilih Kecamatan</option>
                                @foreach ($kecamatan as $item)
                                    <option value="{{ $item->id_kecamatan }}">
                                        {{ $item->nama_kecamatan }}</option>
                                @endforeach
                            </select>
                            <div id="create-kecamatan_id-msg"></div>
                        </div>

                        <div class="col-md-2">
                            <label for="create-kelurahan_id" class="form-label fw-semibold"><i
                                    class="bi bi-building text-success me-1"></i>Kelurahan</label>
                            <select name="kelurahan_id" id="create-kelurahan_id" class="form-select" disabled>
                                <option value="">Pilih Kelurahan</option>
                            </select>
                            <div id="create-kelurahan_id-msg"></div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="create-penanggung_jawab_id" class="form-label fw-semibold"><i
                                        class="bi bi-person text-success me-1"></i>Nama Penanggung
                                    Jawab</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <select name="penanggung_jawab_id" id="create-penanggung_jawab_id" class="form-control">
                                        <option value="" disabled selected>Pilih </option>
                                        @foreach ($penanggungjawab as $item)
                                            <option value="{{ $item->id_penanggung_jawab }}">
                                                {{ $item->nama_penanggung_jawab }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="create-penanggung_jawab_id-msg"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="create-geojson_jalan" class="form-label fw-semibold"><i
                                class="bi bi-pin-map text-success me-1"></i>GeoJSON Jalan</label>
                        <textarea class="form-control" id="geojson" name="geojson_jalan"
                            placeholder="GeoJSON akan otomatis terisi ketika Anda menggambar di peta" rows="5" readonly></textarea>
                        <div id="geojson-msg" class="form-text">Gambar jalan di peta untuk menghasilkan GeoJSON</div>
                        <div id="create-geojson_jalan-msg"></div>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary-light me-2 w-15">
                            <i class="bi bi-skip-backward-fill me-2"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary w-15">
                            <i class="bi bi-floppy me-2"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        // initializeSelect2('.select2', '#form-create')
        $('#create-kecamatan_id').select2({
            placeholder: "Pilih Kecamatan",
            width: '100%'
        });

        $('#create-kelurahan_id').select2({
            placeholder: "Pilih Kelurahan",
            width: '100%'
        });

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

        $(document).ready(function() {
            var peta2 = L.tileLayer('https://mt1.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
                attribution: '© Google Maps',
                maxZoom: 20,
            });

            var peta3 = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
            });

            var peta5 = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            });

            // Inisialisasi peta
            var map = L.map('map', {
                center: [0.9712390563586268, 104.44936044954352],
                zoom: 12,
                layers: [peta5]
            });

            // Tambahkan kontrol layer
            var baseLayers = {
                'Google Maps': peta2,
                'Google Satellite': peta3,
                'OpenStreetMap': peta5,
            };

            L.control.layers(baseLayers).addTo(map);

            var drawnItems = new L.FeatureGroup();
            map.addLayer(drawnItems);

            var drawControl = new L.Control.Draw({
                draw: {
                    polygon: false,
                    polyline: {
                        shapeOptions: {
                            color: '#1a73e8',
                            weight: 5
                        },
                        showLength: true,
                        metric: true
                    },
                    rectangle: false,
                    circle: false,
                    marker: false,
                    circlemarker: false,
                },
                edit: {
                    featureGroup: drawnItems
                }
            });
            map.addControl(drawControl);

            map.on(L.Draw.Event.CREATED, function(e) {
                var type = e.layerType;
                var layer = e.layer;

                if (type === 'polyline') {
                    drawnItems.addLayer(layer);
                    updateGeoJSON();
                }
            });

            // Event handler untuk draw edited
            map.on(L.Draw.Event.EDITED, function(e) {
                updateGeoJSON();
            });

            // Event handler untuk draw deleted
            map.on(L.Draw.Event.DELETED, function(e) {
                updateGeoJSON();
            });

            // Fungsi untuk update GeoJSON di textarea
            function updateGeoJSON() {
                var geoJSON = drawnItems.toGeoJSON();
                if (geoJSON.features.length > 0) {
                    $('#geojson').val(JSON.stringify(geoJSON, null, 2));
                    $('#geojson-msg').html(
                        '<span class="text-success"><i class="bi bi-check-circle"></i> GeoJSON berhasil dihasilkan</span>'
                    );
                } else {
                    $('#geojson').val('');
                    $('#geojson-msg').text('Gambar jalan di peta untuk menghasilkan GeoJSON');
                }
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

                        window.location.href = "/admin/master/jalan";
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
        });
    </script>
@endpush
