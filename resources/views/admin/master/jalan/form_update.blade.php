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

        .rubah {
            height: 45px;
            line-height: 45px;
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
                        <li>Gunakan toolbar edit (pensil dengan garis putus) untuk mengedit garis yang sudah dibuat</li>
                        <li>Gunakan toolbar delete (tong sampah) untuk menghapus garis</li>
                        <li>Gunakan toolbar toolbar draw (pensil) untuk menggambar garis baru</li>
                    </ol>
                </div>

                <div id="map"></div>

                <form id="form-update">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="update-nama_jalan" class="form-label fw-semibold"><i
                                        class="bi bi-signpost-fill text-primary me-1"></i>Nama Jalan</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-signpost-fill"></i></span>
                                    <input type="text" id="update-nama_jalan" name="nama_jalan" class="form-control"
                                        placeholder="Masukkan Nama Jalan" value="{{ $jalan->nama_jalan }}">
                                </div>
                                <div id="update-nama_jalan-msg"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="update-panjang_jalan" class="form-label fw-semibold"><i
                                        class="bi bi-rulers text-primary me-1"></i>Panjang Jalan (M)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-rulers"></i></span>
                                    <input type="number" id="update-panjang_jalan" name="panjang_jalan"
                                        class="form-control" placeholder="Masukkan Panjang"
                                        value="{{ $jalan->panjang_jalan }}">
                                </div>
                                <div id="update-panjang_jalan-msg"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="update-kecamatan_id" class="form-label fw-semibold"><i
                                        class="bi bi-building text-success me-1"></i>Nama Kecamatan</label>
                                <select name="kecamatan_id" id="update-kecamatan_id" class="form-control select2">
                                    @foreach ($kecamatan as $item)
                                        <option value="{{ $item->id_kecamatan }}"
                                            {{ $item->id_kecamatan == $jalan->kecamatan_id ? 'selected' : '' }}>
                                            {{ $item->nama_kecamatan }}</option>
                                    @endforeach
                                </select>
                                <div id="update-kecamatan_id-msg"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label"><i class="bi bi-building text-success me-1"></i>Kelurahan</label>
                            <select name="kelurahan_id" class="form-select fw-semibold" id="update-kelurahan_id" disabled>
                                <option value="">Pilih Kelurahan</option>
                            </select>
                            <div id="update-kelurahan_id-msg"></div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="update-penanggung_jawab_id" class="form-label fw-semibold"><i
                                        class="bi bi-person text-success me-1"></i>Nama Penanggung
                                    Jawab</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <select name="penanggung_jawab_id" id="update-penanggung_jawab_id" class="form-control">
                                        <option value="" disabled selected>Pilih Penanggung Jawab</option>
                                        @foreach ($penanggungjawab as $item)
                                            <option value="{{ $item->id_penanggung_jawab }}"
                                                {{ $item->id_penanggung_jawab == $jalan->penanggung_jawab_id ? 'selected' : '' }}>
                                                {{ $item->nama_penanggung_jawab }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="update-penanggung_jawab_id-msg"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="update-geojson_jalan" class="form-label"><i
                                class="bi bi-pin-map text-success me-1"></i>GeoJSON Jalan</label>
                        <textarea class="form-control fw-semibold" id="geojson" name="geojson_jalan"
                            placeholder="GeoJSON akan otomatis terisi ketika Anda menggambar di peta" rows="5" readonly></textarea>
                        <div id="geojson-msg" class="form-text">Gambar jalan di peta untuk menghasilkan GeoJSON</div>
                        <div id="update-geojson_jalan-msg"></div>
                    </div>
                    <input type="hidden" id="update-geosjon_jalan_database" value='@json($jalan->geojson_jalan)'>
                    <input type="hidden" id="update-id_jalan" value='{{ $jalan->id_jalan }}'>

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
        // initializeSelect2('.select2', '#form-update')

        $('#update-kecamatan_id').select2({
            placeholder: "Pilih Kecamatan",
            width: '100%'
        });

        $('#update-kelurahan_id').select2({
            placeholder: "Pilih Kelurahan",
            width: '100%'
        });

        // fungsi untuk isi kelurahan by kecamatan
        function loadKelurahan(kecamatanId, selectedKelurahanId = null) {
            $('#update-kelurahan_id')
                .empty()
                .append('<option value="">Pilih Kelurahan</option>')
                .prop('disabled', true);

            if (kecamatanId) {
                $.ajax({
                    url: `/pengaduan/${kecamatanId}`,
                    type: "GET",
                    success: function(data) {
                        if (data.length > 0) {
                            data.forEach(function(kel) {
                                let selected = (selectedKelurahanId == kel.id_kelurahan) ? 'selected' :
                                    '';
                                $('#update-kelurahan_id').append(
                                    `<option value="${kel.id_kelurahan}" ${selected}>${kel.nama_kelurahan}</option>`
                                );
                            });

                            $('#update-kelurahan_id').prop('disabled', false).trigger('change');
                        }
                    }
                });
            }
        }

        // === jalankan sekali saat page load ===
        let kecamatanId = $('#update-kecamatan_id').val(); // sudah terisi dari Blade
        let kelurahanId = "{{ $jalan->kelurahan_id }}"; // passing dari controller
        if (kecamatanId) {
            loadKelurahan(kecamatanId, kelurahanId);
        }

        // === kalau user ganti kecamatan manual ===
        $('#update-kecamatan_id').on('change', function() {
            loadKelurahan($(this).val(), null);
        });

        $(document).ready(function() {
            let geojsonFromDatabase = document.getElementById("update-geosjon_jalan_database").value;
            geojsonFromDatabase = JSON.parse(geojsonFromDatabase);
            if (geojsonFromDatabase) {
                geojsonFromDatabase = JSON.parse(geojsonFromDatabase);
            } else {
                // fallback kosong biar tidak error
                geojsonFromDatabase = {
                    "type": "FeatureCollection",
                    "features": []
                };
            }

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

            // Variabel global untuk drawControl
            var drawControl;

            function loadGeoJSONFromDatabase() {
                drawnItems.clearLayers();

                L.geoJSON(geojsonFromDatabase, {
                    style: {
                        color: '#1a73e8',
                        weight: 5
                    },
                    onEachFeature: function(feature, layer) {
                        // Pastikan setiap feature memiliki ID unik
                        if (!feature.id) {
                            feature.id = Date.now() + Math.random().toString(36).substr(2, 9);
                        }
                        layer.feature = feature;
                        drawnItems.addLayer(layer);
                    }
                });

                $('#geojson').val(JSON.stringify(geojsonFromDatabase, null, 2));
                $('#geojson-msg').html(
                    '<span class="text-success"><i class="bi bi-check-circle"></i> GeoJSON dari database telah dimuat</span>'
                );

                // Zoom to the bounds of the GeoJSON
                if (drawnItems.getBounds().isValid()) {
                    map.fitBounds(drawnItems.getBounds().pad(0.1));
                }

                // Inisialisasi atau re-inisialisasi draw control setelah data dimuat
                initDrawControl();
            }

            // Fungsi untuk inisialisasi draw control
            function initDrawControl() {
                // Hapus control yang sudah ada jika ada
                if (drawControl) {
                    map.removeControl(drawControl);
                }

                drawControl = new L.Control.Draw({
                    draw: {
                        polygon: false,
                        polyline: {
                            shapeOptions: {
                                color: '#1a73e8',
                                weight: 5
                            },
                            showLength: true,
                            metric: true,
                            // Tambahkan guidelineOptions
                            guidelineOptions: {
                                color: '#1a73e8',
                                opacity: 0.5
                            }
                        },
                        rectangle: false,
                        circle: false,
                        marker: false,
                        circlemarker: false,
                    },
                    edit: {
                        featureGroup: drawnItems,
                        // Tambahkan opsi edit yang lebih spesifik
                        edit: {
                            selectedPathOptions: {
                                color: '#ff0000',
                                weight: 4
                            }
                        },
                        remove: true
                    }
                });

                map.addControl(drawControl);

                // Setup event handlers
                setupDrawEvents();
            }

            // Setup event handlers untuk draw
            function setupDrawEvents() {
                // Hapus event listeners lama jika ada
                map.off(L.Draw.Event.CREATED);
                map.off(L.Draw.Event.EDITED);
                map.off(L.Draw.Event.DELETED);
                map.off('draw:editstart');
                map.off('draw:editstop');

                map.on(L.Draw.Event.CREATED, function(e) {
                    var type = e.layerType;
                    var layer = e.layer;

                    if (type === 'polyline') {
                        // Tambahkan ID unik untuk layer baru
                        layer.feature = layer.feature || {};
                        layer.feature.id = Date.now() + Math.random().toString(36).substr(2, 9);
                        drawnItems.addLayer(layer);
                        updateGeoJSON();
                    }
                });

                // Event handler untuk draw edited
                map.on(L.Draw.Event.EDITED, function(e) {
                    var layers = e.layers;
                    layers.eachLayer(function(layer) {
                        // Update timestamp atau metadata edit
                        layer.edited = new Date().toISOString();
                    });
                    updateGeoJSON();
                });

                // Event handler untuk draw deleted
                map.on(L.Draw.Event.DELETED, function(e) {
                    updateGeoJSON();
                });

                // Event tambahan untuk monitoring edit
                map.on('draw:editstart', function(e) {
                    console.log('Edit mode started');
                });

                map.on('draw:editstop', function(e) {
                    console.log('Edit mode stopped');
                });
            }

            // Fungsi untuk enable edit mode
            function enableEditMode() {
                if (!drawControl) {
                    console.error('Draw control not initialized');
                    return false;
                }

                // Pastikan toolbar edit tersedia
                if (!drawControl._toolbars || !drawControl._toolbars.edit) {
                    console.error('Edit toolbar not available');
                    return false;
                }

                try {
                    // Nonaktifkan semua mode draw terlebih dahulu
                    if (drawControl._toolbars.draw) {
                        Object.keys(drawControl._toolbars.draw._modes).forEach(function(mode) {
                            var handler = drawControl._toolbars.draw._modes[mode].handler;
                            if (handler && handler.disable) {
                                handler.disable();
                            }
                        });
                    }

                    // Aktifkan edit mode
                    var editHandler = drawControl._toolbars.edit._modes.edit.handler;
                    if (editHandler && editHandler.enable) {
                        editHandler.enable();
                        console.log('Edit mode enabled');
                        return true;
                    } else {
                        console.error('Edit handler not available');
                        return false;
                    }
                } catch (error) {
                    console.error('Error enabling edit mode:', error);
                    return false;
                }
            }

            // Fungsi untuk disable edit mode
            function disableEditMode() {
                if (drawControl && drawControl._toolbars && drawControl._toolbars.edit) {
                    try {
                        var editHandler = drawControl._toolbars.edit._modes.edit.handler;
                        if (editHandler && editHandler.disable) {
                            editHandler.disable();
                            console.log('Edit mode disabled');
                            return true;
                        }
                    } catch (error) {
                        console.error('Error disabling edit mode:', error);
                        return false;
                    }
                }
                return false;
            }

            // Fungsi untuk update GeoJSON di textarea
            function updateGeoJSON() {
                try {
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
                } catch (error) {
                    console.error('Error updating GeoJSON:', error);
                }
            }

            // Load GeoJSON from database when page loads
            $(document).ready(function() {
                loadGeoJSONFromDatabase();

                // Setup event listeners untuk tombol
                setTimeout(function() {
                    setupButtonEvents();
                }, 1000);
            });

            // Setup event listeners untuk tombol
            function setupButtonEvents() {
                $('#edit-mode-btn').off('click').on('click', function() {
                    if (enableEditMode()) {
                        $(this).addClass('active').html('<i class="bi bi-pencil-fill"></i> Sedang Edit');
                        $('#cancel-edit-btn').show();
                    }
                });

                $('#cancel-edit-btn').off('click').on('click', function() {
                    if (disableEditMode()) {
                        $('#edit-mode-btn').removeClass('active').html(
                            '<i class="bi bi-pencil"></i> Mode Edit');
                        $(this).hide();
                    }
                });

                // Sembunyikan tombol batal edit awal
                $('#cancel-edit-btn').hide();
            }

            // Fungsi untuk manual edit (alternatif)
            function startManualEdit() {
                if (drawnItems.getLayers().length === 0) {
                    alert('Tidak ada polyline untuk diedit');
                    return;
                }

                // Highlight semua layers
                drawnItems.eachLayer(function(layer) {
                    layer.setStyle({
                        color: '#ff0000',
                        weight: 6
                    });
                });

                // Enable edit mode
                enableEditMode();
            }

            // Tambahkan event listener untuk klik pada features
            map.on('click', function(e) {
                if (drawControl && drawControl._toolbars && drawControl._toolbars.edit) {
                    var editHandler = drawControl._toolbars.edit._modes.edit.handler;
                    if (editHandler && editHandler.enabled()) {
                        // Cari layer yang diklik
                        var clickedLayer = findLayerAtPoint(e.latlng);
                        if (clickedLayer) {
                            console.log('Layer clicked for editing:', clickedLayer);
                        }
                    }
                }
            });

            // Helper function untuk mencari layer di titik tertentu
            function findLayerAtPoint(latlng) {
                var foundLayer = null;
                drawnItems.eachLayer(function(layer) {
                    if (layer.getBounds && layer.getBounds().contains(latlng)) {
                        foundLayer = layer;
                    } else if (layer.getLatLng && layer.getLatLng().distanceTo(latlng) < 10) {
                        foundLayer = layer;
                    }
                });
                return foundLayer;
            }

            $('#form-update').submit(function(e) {
                e.preventDefault()

                formMode = 'update'

                const formData = new FormData(this)
                formData.append('_token', getCsrfToken())
                formData.append('_method', 'put')

                callApi(formData, `{{ url()->current() }}`)
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
