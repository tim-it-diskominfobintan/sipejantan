@extends('layouts.admin.main')
@section('content')
    <style>
        .form-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin: 20px auto;
            max-width: 900px;
        }

        .form-header {
            background: linear-gradient(120deg, #4e73df 0%, #224abe 100%);
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
            margin: -30px -30px 30px -30px;
        }

        .form-section {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e3e6f0;
        }

        .form-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .section-title {
            font-size: 1.2rem;
            color: #4e73df;
            margin-bottom: 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 10px;
            font-size: 1.4rem;
        }

        .form-label {
            font-weight: 500;
            color: #4e4e4e;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select,
        .form-textarea {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #d1d3e2;
            transition: all 0.3s;
        }

        .form-control:focus,
        .form-select:focus,
        .form-textarea:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        }

        .form-textarea {
            min-height: 400px;
            resize: vertical;
        }

        .input-group-text {
            background-color: #f8f9fc;
            border-radius: 8px 0 0 8px;
        }

        .btn-primary {
            background: linear-gradient(120deg, #4e73df 0%, #224abe 100%);
            border: none;
            border-radius: 8px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-secondary {
            background-color: #f8f9fc;
            color: #858796;
            border: 1px solid #d1d3e2;
            border-radius: 8px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-secondary:hover {
            background-color: #eaecf4;
            color: #6c757d;
        }

        .required-field::after {
            content: " *";
            color: #e74a3b;
        }

        .form-note {
            background-color: #f8f9fc;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #4e73df;
            margin-top: 20px;
        }

        .upload-card {
            border: 2px dashed #d1d3e2;
            border-radius: 8px;
            background-color: #f8f9fc;
            cursor: pointer;
            transition: all 0.3s;
        }

        .upload-card:hover {
            border-color: #4e73df;
            background-color: #eaf0ff;
        }

        .preview-wrapper {
            max-height: 180px;
            max-width: 900px;
            border-radius: 6px;
            border: 1px solid #1273d3;
        }

        .remove-btn {
            position: absolute;
            top: -6px;
            right: -6px;
            background: rgba(220, 53, 69, 0.9);
            border: none;
            color: #fff;
            border-radius: 50%;
            font-size: 14px;
            width: 22px;
            height: 22px;
            cursor: pointer;
            line-height: 1;
        }

        .upload-box {
            cursor: pointer;
            background: #f8f9fa;
            height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .upload-box img {
            max-height: 180%;
            max-width: 100%;
        }

        input:disabled,
        select:disabled,
        textarea:disabled {
            background-color: #E9ECEF !important;
            color: #6c757d !important;
            cursor: not-allowed;
            opacity: 1 !important;
        }

        input[readonly],
        textarea[readonly] {
            background-color: #fdfdfe !important;
            color: #6c757d !important;
            cursor: not-allowed;
        }

        .select2-container .select2-selection--single {
            height: calc(2.7rem + 2px) !important;
            /* sesuaikan dengan input */
            padding: 0.375rem 0.75rem;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
        }

        .form-control[readonly],
        .form-select[readonly] {
            background-color: #e9ecef !important;
            /* warna abu abu seperti disabled select */
            opacity: 1;
            /* supaya jelas, tidak transparan */
        }

        /* Samakan tinggi & border select2 dengan input bootstrap */
        .select2-container .select2-selection--single {
            height: calc(2.7rem + 2px) !important;
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

        @media (max-width: 768px) {
            .form-container {
                padding: 15px;
                margin: 10px;
            }

            .section-title {
                font-size: 1rem;
            }

            /* Foto Identitas di tengah di mobile */
            .upload-box {
                height: 180px;
            }

            #upload-box {
                width: 100%;
                margin: 0 auto;
            }

            #preview-image {
                max-height: 160px;
                margin: 0 auto;
                display: block;
            }

            /* Pastikan kolom kiri & kanan di bawah-bawah */
            .row>.col-8,
            .row>.col-4 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            /* Jarak antar section */
            .form-section {
                padding-bottom: 20px;
                margin-bottom: 25px;
            }

            /* Tombol agar tidak nempel kiri kanan */
            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 10px;
            }

            .d-flex.justify-content-between button {
                width: 100%;
            }

            /* Preview gambar laporan */
            #preview-foto_laporan .col-3 {
                flex: 0 0 48%;
                max-width: 48%;
            }

            #preview-foto_laporan img {
                height: 100px !important;
                object-fit: cover;
            }
        }

        @media (max-width: 576px) {
            .upload-box {
                height: 150px;
            }

            .section-title {
                font-size: 0.95rem;
            }

            .form-control,
            .form-select,
            textarea {
                font-size: 0.9rem;
            }
        }
    </style>
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Tambah Laporan</h5>
            </div>
            <div class="card-body">
                <form id="form-create">
                    <!-- Data Pelapor Section -->
                    <div class="form-section">
                        <div class="section-title"><i class="bi bi-person-circle"></i> Data Pelapor</div>
                        <div class="row">
                            <div class="col-8">
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label for="create-data_teknisi" class="form-label">Pelapor <span
                                                class="text-danger">*</span></label>
                                        <select name="data_teknisi" id="data_teknisi" class="form-control select2">
                                            <option value="" selected disabled>Pilih Pelapor </option>
                                            @if (!$user->teknisi_id)
                                                <option value="umum">Umum</option>
                                            @endif
                                            @foreach ($teknisi as $item)
                                                <option value="{{ $item->id_teknisi }}">
                                                    {{ $item->nama_teknisi }}</option>
                                            @endforeach
                                        </select>
                                        <div id="create-data-data_teknisi-msg"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="create-nik" class="form-label">NIK <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="create-nik" name="nik"
                                            placeholder="Masukkan NIK">
                                        <div id="create-nik-msg"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="create-nama" class="form-label">Nama Lengkap <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="create-nama" name="nama"
                                            placeholder="Masukkan Nama Lengkap">
                                        <div id="create-nama-msg"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="create-email" class="form-label">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="create-email" name="email"
                                            placeholder="contoh@email.com">
                                        <div id="create-email-msg"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="create-no_hp" class="form-label">No Telp <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="create-no_hp"
                                            placeholder="0812 xxxx xxxx" name="no_hp">
                                        <div id="create-no_hp-msg"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="create-kecamatan_id" class="form-label">Kecamatan <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select select2" id="create-kecamatan_id" name="kecamatan_id">
                                            <option value="">Pilih Kecamatan</option>
                                            @foreach ($kecamatan as $item)
                                                <option value="{{ $item->id_kecamatan }}">{{ $item->nama_kecamatan }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div id="create-kecamatan_id-msg"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="create-kelurahan" class="form-label">Kelurahan <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select select2" id="create-kelurahan_id" name="kelurahan_id">
                                            <option value="">Pilih Kelurahan</option>
                                        </select>
                                        <div id="create-kelurahan_id-msg"></div>
                                    </div>
                                    <div class="col-md-8">
                                        <label for="create-alamat" class="form-label">Alamat Lengkap <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" id="create-alamat" name="alamat" rows="1" placeholder="Masukkan alamat lengkap"></textarea>
                                        <div id="create-alamat-msg"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="mb-3">
                                    <label class="form-label">Foto Identitas</label>
                                    <div class="upload-box border border-2 border-dashed rounded p-4 text-center position-relative"
                                        id="upload-box">
                                        <input type="file" class="d-none" id="create-foto_identitas"
                                            name="foto_identitas" accept="image/*">
                                        <div id="upload-text">
                                            <i class="bi bi-person-badge fs-1 text-success"></i>
                                            <p class="mb-1">Klik untuk unggah foto identitas</p>
                                            <small class="text-muted">Format JPG/PNG, maksimal 2MB</small>
                                        </div>
                                        <!-- Preview gambar akan muncul di sini -->
                                        <img id="preview-image" src="" alt="Preview Foto"
                                            class="img-fluid d-none rounded" style="max-height:150px; object-fit:cover;">
                                    </div>
                                    <div id="create-foto_identitas-msg"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Laporan Section -->
                    <div class="form-section">
                        <div class="section-title"><i class="bi bi-file-text"></i> Detail Laporan</div>
                        <div class="row">
                            <div class="col-8">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="create-no_laporan" class="form-label required-field">No
                                            Laporan</label>
                                        <input type="text" name="no_laporan" id="create-no_laporan"
                                            class="form-control" readonly>
                                        <div id="create-no_laporan-msg"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="create-asset_id" class="form-label required-field">Jenis Asset</label>
                                        <select class="form-select select2" id="create-asset_id" name="asset_id"
                                            {{ $selectedAsset ? 'disabled' : '' }}>
                                            <option value="">Pilih Jenis Asset</option>
                                            @foreach ($asset as $item)
                                                <option value="{{ $item->id_asset }}"
                                                    {{ $selectedAsset && $selectedAsset->id_asset == $item->id_asset ? 'selected' : '' }}>
                                                    {{ $item->nama_asset }}</option>
                                            @endforeach
                                        </select>
                                        <div id="create-asset_id-msg"></div>
                                    </div>

                                    @if ($selectedAsset)
                                        <input type="hidden" name="asset_id" value="{{ $selectedAsset->id_asset }}">
                                    @endif

                                    <div class="col-md-4 mb-3">
                                        <label for="create-tanggal_laporan" class="form-label required-field">Tanggal
                                            Laporan</label>
                                        <input type="date" class="form-control" id="create-tanggal_laporan"
                                            name="tanggal_laporan">
                                        <div id="create-tanggal_laporan-msg"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="create-deskripsi_laporan" class="form-label required-field">Deskripsi
                                            Laporan</label>
                                        <textarea class="form-control form-textarea" id="create-deskripsi_laporan" name="deskripsi_laporan" rows="5"
                                            placeholder="Jelaskan secara detail masalah atau kerusakan yang ditemukan"></textarea>
                                        <div id="create-deskripsi_laporan-msg"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            Foto Laporan *
                                            <small class="text-muted">Format JPG/PNG, maksimal 2MB</small>
                                        </label>
                                        <input type="file" class="form-control" id="create-foto_laporan"
                                            name="foto_laporan[]" accept="image/*" multiple>
                                    </div>
                                    <!-- Tempat preview -->
                                    <div id="preview-foto_laporan" class="row g-2"></div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-between">
                        <button type="reset" class="btn btn-secondary">
                            <i class="bi bi-arrow-repeat me-2"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-2"></i> Kirim
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $('#data_teknisi').on('change', function() {
            let data_teknisi = $(this).val();
            if (data_teknisi !== 'umum') {
                $.ajax({
                    url: "{{ route('data_teknisi', '') }}/" + data_teknisi,
                    type: 'GET',
                    success: function(res) {
                        // $('#data_teknisi').val(res.nik_teknisi).attr('disabled', true);
                        $('#create-nik').val(res.nik_teknisi).prop('readonly', true);
                        $('#create-nama').val(res.nama_teknisi).prop('readonly', true);
                        $('#create-email').val(res.email_teknisi).prop('readonly', true);
                        $('#create-no_hp').val(res.hp_teknisi).prop('readonly', true);
                        $('#create-kecamatan_id').val(res.kecamatan_id).trigger('change').prop(
                            'disabled', true);
                        $('#create-kelurahan_id').val(res.kelurahan_id).trigger('change').prop(
                            'disabled', true);
                        $('#create-alamat').val(res.alamat_teknisi).prop('readonly', true);

                        if (res.foto_teknisi) {
                            $('#preview-image')
                                .attr('src', '/storage/' + res.foto_teknisi)
                                .removeClass('d-none');
                            $('#upload-text').addClass('d-none');
                        } else {
                            $('#preview-image').addClass('d-none');
                            $('#upload-text').removeClass('d-none');
                        }

                        $('#create-foto_teknisi').prop('disabled', true);

                        $('#create-kecamatan_id')
                            .val(res.kecamatan_id)
                            .trigger('change') // ini akan load kelurahan via ajax
                            .prop('disabled', true);

                        // 🚀 tunggu ajax kelurahan selesai, lalu set kelurahan
                        $(document).one('kelurahanLoaded', function() {
                            $('#create-kelurahan_id')
                                .val(res.kelurahan_id)
                                .trigger('change');
                        });

                        $('#create-no_laporan').val(generateNoLaporan('teknisi'));
                    }
                });
            } else {
                // reset form kalau pilih "umum"
                $('#create-nik').val('').prop('readonly', false);
                $('#create-nama').val('').prop('readonly', false);
                $('#create-email').val('').prop('readonly', false);
                $('#create-no_hp').val('').prop('readonly', false);
                $('#create-kecamatan_id').val('').trigger('change').prop('disabled', false);
                $('#create-alamat').val('').prop('readonly', false);
                $('#create-kelurahan_id').prop('disabled', false);
                $('#preview-image').attr('src', '').addClass('d-none');
                $('#upload-text').removeClass('d-none');
                $('#create-foto_teknisi').prop('disabled', false);

                $('#create-no_laporan').val(generateNoLaporan('umum'));
            }
        });

        // kalau user login sebagai teknisi, langsung pilih dan trigger
        let teknisiId = "{{ Auth::user()->teknisi_id ?? '' }}";
        if (teknisiId) {
            $('#data_teknisi').val(teknisiId).trigger('change');
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

                    window.location.href = "/admin/laporan";
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
    </script>
    <script>
        $('#create-kecamatan_id').on('change', function() {
            let kecamatanId = $(this).val();

            $('#create-kelurahan_id')
                .empty()
                .append('<option value="">Pilih Kelurahan</option>');

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

                            // 🚀 kasih signal kalau kelurahan sudah selesai load
                            $(document).trigger('kelurahanLoaded');
                        }
                    }
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('form-create');
            const uploadBox = document.getElementById("upload-box");
            const fileInput = document.getElementById("create-foto_identitas");
            const previewImage = document.getElementById("preview-image");
            const uploadText = document.getElementById("upload-text");

            // Klik area box → buka input file
            uploadBox.addEventListener("click", () => fileInput.click());

            // Tampilkan preview ketika pilih file
            fileInput.addEventListener("change", (event) => {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        previewImage.classList.remove("d-none");
                        uploadText.classList.add("d-none"); // Sembunyikan teks upload
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Set tanggal hari ini sebagai default
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('tanggal_laporan').value = today;

            // Reset form border color on input
            form.querySelectorAll('input, select, textarea').forEach(field => {
                field.addEventListener('input', function() {
                    this.style.borderColor = '';
                });
            });
        });

        const inputFoto = document.getElementById('create-foto_laporan');
        const preview = document.getElementById('preview-foto_laporan');

        // simpan file yang dipilih
        let selectedFiles = [];

        inputFoto.addEventListener('change', function(e) {
            selectedFiles = Array.from(e.target.files); // simpan file baru
            renderPreview();
        });

        function renderPreview() {
            preview.innerHTML = "";

            selectedFiles.forEach((file, index) => {
                if (!file.type.startsWith('image/')) return;

                const reader = new FileReader();
                reader.onload = function(event) {
                    const col = document.createElement('div');
                    col.classList.add('col-3');

                    col.innerHTML = `
                        <div class="card shadow-sm position-relative">
                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 rounded-circle"
                                    onclick="removeImage(${index})">
                                &times;
                            </button>
                            <img src="${event.target.result}" 
                                class="card-img-top rounded" 
                                style="height:120px; object-fit:cover;">
                        </div>
                    `;
                    preview.appendChild(col);
                };
                reader.readAsDataURL(file);
            });
        }

        // fungsi hapus file
        function removeImage(index) {
            selectedFiles.splice(index, 1); // hapus dari array
            updateFileInput();
            renderPreview();
        }

        // update input file supaya sesuai selectedFiles
        function updateFileInput() {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => dataTransfer.items.add(file));
            inputFoto.files = dataTransfer.files;
        }
    </script>
@endpush
