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

        #laporan-preview-container .preview-wrapper {
            position: relative;
            width: 280px;
            height: 200px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fafafa;
        }

        #laporan-preview-container .preview-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        #laporan-preview-container .remove-btn {
            position: absolute;
            top: 6px;
            right: 6px;
            background: rgba(0, 0, 0, .6);
            color: #fff;
            border: none;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            cursor: pointer;
            line-height: 20px;
            font-size: 16px;
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
            max-height: 100%;
            max-width: 100%;
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
    </style>
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Ubah Laporan</h5>
            </div>
            <div class="card-body">
                <form id="form-update">
                    <!-- Data Pelapor Section -->
                    <div class="form-section">
                        <div class="section-title"><i class="bi bi-person-circle"></i> Data Pelapor</div>
                        <div class="row">
                            <div class="col-8">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="update-nik" class="form-label">NIK <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="update-nik" name="nik"
                                            placeholder="Masukkan NIK" value="{{ $pelapor->nik }}" readonly>
                                        <div id="update-nik-msg"></div>
                                    </div>
                                    <div class="col-md-8">
                                        <label for="update-nama" class="form-label">Nama Lengkap <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="update-nama" name="nama"
                                            placeholder="Masukkan Nama Lengkap" value="{{ $pelapor->nama }}">
                                        <div id="update-nama-msg"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="update-email" class="form-label">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="update-email" name="email"
                                            placeholder="contoh@email.com" value="{{ $pelapor->email }}">
                                        <div id="update-email-msg"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="update-no_hp" class="form-label">No Telp <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="update-no_hp"
                                            placeholder="0812 xxxx xxxx" name="no_hp" value="{{ $pelapor->no_hp }}">
                                        <div id="update-no_hp-msg"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="update-kecamatan_id" class="form-label">Kecamatan <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select select2" id="update-kecamatan_id" name="kecamatan_id">
                                            <option value="">Pilih Kecamatan</option>
                                            @foreach ($kecamatan as $item)
                                                <option value="{{ $item->id_kecamatan }}"
                                                    {{ $item->id_kecamatan == $pelapor->kecamatan_id ? 'selected' : '' }}>
                                                    {{ $item->nama_kecamatan }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div id="update-kecamatan_id-msg"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="update-kelurahan" class="form-label">Kelurahan <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select select2" id="update-kelurahan_id" name="kelurahan_id">
                                            <option value="">Pilih Kelurahan</option>
                                            @foreach ($kelurahan as $item)
                                                <option value="{{ $item->id_kelurahan }}"
                                                    {{ $item->id_kelurahan == $pelapor->kelurahan_id ? 'selected' : '' }}>
                                                    {{ $item->nama_kelurahan }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div id="update-kelurahan_id-msg"></div>
                                    </div>
                                    <div class="col-md-8">
                                        <label for="update-alamat" class="form-label">Alamat Lengkap <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" id="update-alamat" name="alamat" rows="1" placeholder="Masukkan alamat lengkap">{{ $pelapor->alamat }}</textarea>
                                        <div id="update-alamat-msg"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="mb-3">
                                    <label class="form-label">Foto Identitas</label>
                                    <div class="upload-box border border-2 border-dashed rounded p-4 text-center position-relative"
                                        id="upload-box">
                                        <input type="file" class="d-none" id="update-foto_identitas"
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
                                    <div id="update-foto_identitas-msg"></div>
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
                                        <label for="update-no_laporan" class="form-label required-field">No
                                            Laporan</label>
                                        <input type="text" name="no_laporan" id="update-no_laporan"
                                            class="form-control" readonly value="{{ $laporan->no_laporan }}">
                                        <div id="update-no_laporan-msg"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="update-asset_id" class="form-label required-field">Jenis Asset</label>
                                        <select class="form-select select2" id="update-asset_id" name="asset_id">
                                            <option value="">Pilih Jenis Asset</option>
                                            @foreach ($asset as $item)
                                                <option value="{{ $item->id_asset }}"
                                                    {{ $item->id_asset == $laporan->asset_id ? 'selected' : '' }}>
                                                    {{ $item->nama_asset }}</option>
                                            @endforeach
                                        </select>
                                        <div id="update-asset_id-msg"></div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="update-tanggal_laporan" class="form-label required-field">Tanggal
                                            Laporan</label>
                                        <input type="date" class="form-control" id="update-tanggal_laporan"
                                            name="tanggal_laporan" value="{{ $laporan->tanggal_laporan }}">
                                        <div id="update-tanggal_laporan-msg"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="update-deskripsi_laporan" class="form-label required-field">Deskripsi
                                            Laporan</label>
                                        <textarea class="form-control form-textarea" id="update-deskripsi_laporan" name="deskripsi_laporan" rows="5"
                                            placeholder="Jelaskan secara detail masalah atau kerusakan yang ditemukan">{{ $laporan->deskripsi_laporan }}</textarea>
                                        <div id="update-deskripsi_laporan-msg"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Foto Laporan</label>
                                        <div class="upload-box border border-2 border-dashed rounded p-4 text-center position-relative"
                                            id="foto-laporan-area">
                                            <input type="file" class="d-none" id="update-foto_laporan"
                                                name="foto_laporan" accept="image/*">
                                            <div id="laporan-preview-container">
                                                <i class="bi bi-person-badge fs-1 text-success"></i>
                                                <p class="mb-1">Klik untuk unggah foto laporan</p>
                                                <small class="text-muted">Format JPG/PNG, maksimal 2MB</small>
                                            </div>
                                            <!-- Preview gambar akan muncul di sini -->
                                            <img id="preview-placeholder" src="" alt="Preview Foto"
                                                class="img-fluid d-none rounded"
                                                style="max-height:150px; object-fit:cover;">
                                        </div>
                                        <div id="update-foto_identitas-msg"></div>
                                    </div>
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
    </script>
    <script>
        initializeSelect2('.select2', '#form-update')
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('form-update');
            const uploadBox = document.getElementById("upload-box");
            const fileInput = document.getElementById("update-foto_identitas");
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

            const uploadArea = document.getElementById("foto-laporan-area");
            const inputFile = document.getElementById("update-foto_laporan");
            const previewContainer = document.getElementById("laporan-preview-container");
            const placeholder = document.getElementById("preview-placeholder");

            // Klik area kosong → buka dialog file (tapi abaikan klik pada gambar/tombol hapus)
            uploadArea.addEventListener("click", (e) => {
                if (e.target.closest(".remove-btn") || e.target.tagName === "IMG") return;
                inputFile.click();
            });

            // Render ulang semua preview dari FileList
            function renderPreviews(files) {
                previewContainer.innerHTML = "";
                [...files].forEach((file) => {
                    if (!file.type.startsWith("image/")) return;
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const wrapper = document.createElement("div");
                        wrapper.className = "preview-wrapper";
                        wrapper.innerHTML = `
                        <img src="${e.target.result}" alt="Preview">
                        <button type="button" class="remove-btn" aria-label="Hapus">&times;</button>
                    `;
                        // Tombol hapus
                        wrapper.querySelector(".remove-btn").addEventListener("click", (ev) => {
                            ev.stopPropagation(); // ⛔ cegah bubble ke uploadArea
                            // Hitung index wrapper saat INI (bukan index lama)
                            const indexNow = [...previewContainer.children].indexOf(wrapper);
                            const dt = new DataTransfer();
                            [...inputFile.files].forEach((f, i) => {
                                if (i !== indexNow) dt.items.add(f);
                            });
                            inputFile.files = dt.files;
                            renderPreviews(inputFile.files);
                        });
                        previewContainer.appendChild(wrapper);
                    };
                    reader.readAsDataURL(file);
                });
                placeholder.hidden = files.length > 0;
            }

            // Saat pilih file baru
            inputFile.addEventListener("change", function() {
                // Jika dialog dibatalkan, jangan hapus preview yang ada
                if (!this.files || this.files.length === 0) return;
                renderPreviews(this.files);
            });

            // Reset form border color on input
            form.querySelectorAll('input, select, textarea').forEach(field => {
                field.addEventListener('input', function() {
                    this.style.borderColor = '';
                });
            });
        });
    </script>
@endpush
