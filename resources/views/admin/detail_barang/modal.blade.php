<style>
    .modal-content {
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        border: none;
    }

    .modal-header {
        background: linear-gradient(120deg, #4e73df 0%, #224abe 100%);
        color: white;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        padding: 15px 20px;
    }

    .modal-title {
        font-weight: 600;
        font-size: 1.3rem;
    }

    .btn-close {
        filter: invert(1);
        opacity: 0.8;
    }

    .btn-close:hover {
        opacity: 1;
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 5px;
        color: #4e4e4e;
    }

    .form-control,
    .form-select {
        border-radius: 8px;
        padding: 10px 15px;
        border: 1px solid #d1d3e2;
        transition: all 0.3s;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
    }

    .input-group-text {
        background-color: #f8f9fc;
        border-radius: 8px 0 0 8px;
    }

    .btn-secondary-light {
        background-color: #f8f9fc;
        color: #858796;
        border: 1px solid #d1d3e2;
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-secondary-light:hover {
        background-color: #eaecf4;
        color: #6c757d;
    }

    .preview-container {
        border: 2px dashed #d1d3e2;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        background-color: #f8f9fc;
        margin-top: 10px;
        display: none;
    }

    .preview-image {
        max-width: 100%;
        max-height: 200px;
        border-radius: 5px;
    }

    .form-section {
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e3e6f0;
    }

    .section-title {
        font-size: 1.1rem;
        color: #4e73df;
        margin-bottom: 15px;
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .section-title i {
        margin-right: 10px;
    }

    .detail-modal .modal-content {
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        border: none;
    }

    .detail-modal .modal-header {
        background: linear-gradient(120deg, #4e73df 0%, #224abe 100%);
        color: white;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        padding: 15px 20px;
    }

    .detail-modal .modal-title {
        font-weight: 600;
        font-size: 1.3rem;
    }

    .detail-modal .btn-close {
        filter: invert(1);
        opacity: 0.8;
    }

    .detail-modal .btn-close:hover {
        opacity: 1;
    }

    .detail-modal .modal-body {
        padding: 25px;
    }

    .detail-section {
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e3e6f0;
    }

    .detail-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .section-title {
        font-size: 1.1rem;
        color: #4e73df;
        margin-bottom: 15px;
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .section-title i {
        margin-right: 10px;
    }

    .detail-item {
        display: flex;
        margin-bottom: 12px;
    }

    .detail-label {
        min-width: 180px;
        font-weight: 500;
        color: #6c757d;
    }

    .detail-value {
        flex-grow: 1;
        color: #343a40;
    }

    .badge-stok {
        font-size: 0.85rem;
        padding: 0.35em 0.65em;
        border-radius: 6px;
    }

    .product-image {
        width: 100%;
        max-height: 250px;
        object-fit: contain;
        border-radius: 8px;
        border: 1px solid #dee2e6;
        padding: 5px;
        background-color: #f8f9fc;
    }

    .image-placeholder {
        width: 100%;
        height: 200px;
        background-color: #f8f9fc;
        border: 2px dashed #d1d3e2;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #858796;
    }

    .garansi-info {
        background-color: #f8f9fc;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid #4e73df;
    }

    .garansi-expired {
        border-left-color: #e74a3b;
    }

    .garansi-warning {
        border-left-color: #f6c23e;
    }

    .detail-footer {
        background-color: #f8f9fc;
        border-top: 1px solid #e3e6f0;
        padding: 15px 25px;
        border-bottom-left-radius: 12px;
        border-bottom-right-radius: 12px;
        text-align: right;
    }

    .btn-detail {
        border-radius: 8px;
        padding: 8px 16px;
        font-weight: 500;
    }
</style>

<div class="modal modal-blur fade" id="modal-create" tabindex="-1" aria-labelledby="modal-createLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="form-create">
                <div class="modal-header">
                    <h1 class="modal-title" id="modal-createLabel"><i class="bi bi-box-seam me-2"></i> Tambah Data
                        Barang
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-section">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="create-kode_barang" class="form-label">Kode Barang</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-key"></i></span>
                                        <input type="text" id="create-kode_barang" name="kode_barang"
                                            class="form-control" placeholder="Kode akan digenerate otomatis" readonly>
                                        <button type="button" class="btn btn-outline-secondary" id="generate-kode">
                                            <i class="bi bi-arrow-repeat me-2"></i>
                                        </button>
                                    </div>
                                    <div id="create-kode_barang-msg" class="form-text">Kode barang akan digenerate
                                        otomatis</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="create-tanggal_masuk" class="form-label">Tanggal Masuk <span
                                            class="text-danger">*</span></label></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                        <input type="date" id="create-tanggal_masuk" name="tanggal_masuk"
                                            class="form-control">
                                    </div>
                                    <div id="create-tanggal_masuk-msg"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="create-tanggal_mulai_garansi" class="form-label">Tanggal Mulai
                                        Garansi <span class="text-danger">*</span></label></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                        <input type="date" id="create-tanggal_mulai_garansi"
                                            name="tanggal_mulai_garansi" class="form-control">
                                    </div>
                                    <div id="create-tanggal_mulai_garansi-msg"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="create-lama_garansi" class="form-label">Lama Garansi (Bulan) <span
                                            class="text-danger">*</span></label></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                        <input type="number" id="create-lama_garansi" name="lama_garansi"
                                            class="form-control" placeholder="0" min="0">
                                        <span class="input-group-text">Bulan</span>
                                    </div>
                                    <div id="create-lama_garansi-msg"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-group">
                            <label for="create-foto_detail_barang" class="form-label">Upload Foto Barang <span
                                    class="text-danger">*</span></label></label>
                            <input type="file" id="create-foto_detail_barang" name="foto_detail_barang"
                                class="form-control" accept="image/*">
                            <div class="form-text">Maksimal ukuran file 2MB</div>
                            <div id="create-foto_detail_barang-msg"></div>
                        </div>
                        <div class="preview-container" id="foto-preview">
                            <p>Pratinjau Foto:</p>
                            <img src="#" alt="Pratinjau Foto" class="preview-image mb-2">
                            <button type="button" class="btn btn-sm btn-danger" id="remove-foto">
                                <i class="bi bi-trash"></i> Hapus Foto
                            </button>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-group">
                            <label for="create-keterangan_barang" class="form-label">Keterangan Barang <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                <input type="text" id="create-keterangan_barang" name="keterangan_barang"
                                    class="form-control" placeholder="Masukkan Keterangan Barang">
                            </div>
                            <div id="create-keterangan_barang-msg"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary-light" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-floppy me-2"></i> Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal modal-blur fade" id="modal-update" tabindex="-1" aria-labelledby="modal-updateLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="form-update">
                <div class="modal-header">
                    <h1 class="modal-title" id="modal-updateLabel"><i class="bi bi-box-seam me-2"></i> Ubah Data
                        Barang
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-section">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="update-kode_barang" class="form-label">Kode Barang</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-key"></i></span>
                                        <input type="text" id="update-kode_barang" name="kode_barang"
                                            class="form-control" placeholder="Kode akan digenerate otomatis" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="update-tanggal_masuk" class="form-label">Tanggal Masuk <span
                                            class="text-danger">*</span></label></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                        <input type="date" id="update-tanggal_masuk" name="tanggal_masuk"
                                            class="form-control">
                                    </div>
                                    <div id="update-tanggal_masuk-msg"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="update-tanggal_mulai_garansi" class="form-label">Tanggal Mulai
                                        Garansi <span class="text-danger">*</span></label></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                        <input type="date" id="update-tanggal_mulai_garansi"
                                            name="tanggal_mulai_garansi" class="form-control">
                                    </div>
                                    <div id="update-tanggal_mulai_garansi-msg"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="update-lama_garansi" class="form-label">Lama Garansi (Bulan) <span
                                            class="text-danger">*</span></label></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                        <input type="number" id="update-lama_garansi" name="lama_garansi"
                                            class="form-control" placeholder="0" min="0">
                                        <span class="input-group-text">Bulan</span>
                                    </div>
                                    <div id="update-lama_garansi-msg"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-group">
                            <label for="update-foto_detail_barang" class="form-label">Upload Foto Barang <span
                                    class="text-danger">*</span></label></label>
                            <input type="file" id="update-foto_detail_barang" name="foto_detail_barang"
                                class="form-control" accept="image/*">
                            <div class="form-text">Maksimal ukuran file 2MB</div>
                            <div id="update-foto_detail_barang-msg"></div>
                        </div>
                        <div class="preview-container" id="foto-preview">
                            <p>Pratinjau Foto:</p>
                            <img src="#" alt="Pratinjau Foto" class="preview-image mb-2">
                            <button type="button" class="btn btn-sm btn-danger" id="remove-foto">
                                <i class="bi bi-trash"></i> Hapus Foto
                            </button>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-group">
                            <label for="update-keterangan_barang" class="form-label">Keterangan Barang <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                <input type="text" id="update-keterangan_barang" name="keterangan_barang"
                                    class="form-control" placeholder="Masukkan Keterangan Barang">
                            </div>
                            <div id="update-keterangan_barang-msg"></div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="update-id_detail_barang" name="id_detail_barang">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary-light" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-floppy me-2"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="barcodeModal" tabindex="-1" aria-labelledby="barcodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="barcodeModalLabel">Barcode Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div id="qrcode" class="d-flex justify-content-center"></div>
                <p class="mt-3 fw-bold" id="barcode-kode-text"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="print-barcode">Cetak</button>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-blur fade" id="modal-opname" tabindex="-1" aria-labelledby="modal-opnameLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-opname">
                <div class="modal-header">
                    <h1 class="modal-title" id="modal-createLabel"><i class="bi bi-boxes me-2"></i> Tambah Stok
                        Opname</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="form-group mb-3 col-6">
                            <label for="opname-barang" class="form-label">Nama Barang</label>
                            <input type="text" id="opname-barang" class="form-control" disabled>
                            <div id="opname-barang-msg"></div>
                        </div>

                        <div class="form-group mb-3 col-6">
                            <label for="opname-tanggal_opname" class="form-label">Tanggal Opname</label>
                            <input type="date" id="opname-tanggal_opname" name="tanggal_opname"
                                class="form-control">
                            <div id="opname-tanggal_opname-msg"></div>
                        </div>

                        <div class="form-group mb-3 col-6">
                            <label for="opname-jenis_opname" class="form-label">Jenis Opname</label>
                            <select name="jenis_opname" id="opname-jenis_opname" class="form-select">
                                <option value="" disabled selected>Pilih Jenis Opname</option>
                                <option value="masuk">Masuk</option>
                                <option value="keluar">Keluar</option>
                                <option value="rusak">Rusak</option>
                            </select>
                            <div id="opname-jenis_opname-msg"></div>
                        </div>

                        <div class="form-group mb-3 col-6">
                            <label for="opname-jumlah_opname" class="form-label">Jumlah Opname</label>
                            <input type="number" id="opname-jumlah_opname" name="jumlah_opname"
                                class="form-control" min="0">
                            <div id="opname-jumlah_opname-msg"></div>
                        </div>

                        <div class="form-group mb-3 col-6" id="container-no_bukti">
                            <label for="opname-no_bukti" class="form-label">No Bukti / ID Perbaikan</label>
                            <!-- Default: text input -->
                            <input type="text" id="opname-no_bukti" name="no_bukti" class="form-control"
                                placeholder="No Bukti">
                            <div id="opname-no_bukti-msg"></div>
                        </div>

                        <div class="form-group mb-3 col-6">
                            <label for="opname-keterangan" class="form-label">Keterangan</label>
                            <input type="text" id="opname-keterangan" name="keterangan" class="form-control">
                            <div id="opname-keterangan-msg"></div>
                        </div>

                        <input type="hidden" id="opname-detail_barang_id" name="detail_barang_id"
                            class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                            class="bi bi-x-circle me-2"></i> Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-2"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal List Opname -->
<div class="modal fade" id="modal-list-opname" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content rounded-3 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-list-check me-2"></i> Riwayat Stok Opname</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-hover" id="table-opname">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal Opname</th>
                            <th>Jenis Opname</th>
                            <th>Jumlah</th>
                            <th>No Bukti / ID Perbaikan</th>
                            <th>Keterangan</th>
                            <th>Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data akan diisi lewat JavaScript -->
                    </tbody>
                </table>
            </div>
            <div class="detail-footer d-flex justify-content-end">
                <button type="button" class="btn btn-secondary btn-detail" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

{{-- detail barang --}}
<div class="modal fade detail-modal" id="detailBarangModal" tabindex="-1" aria-labelledby="detailBarangModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailBarangModalLabel"><i class="bi bi-info-circle me-2"></i> Detail
                    Barang
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="detail-section row align-items-center">
                    <!-- Kolom Kiri: Informasi Barang -->
                    <div class="col-md-9">
                        <div class="section-title"><i class="bi bi-upc-scan"></i> Informasi Kode Barang</div>
                        <div class="detail-item">
                            <span class="detail-label">Kode Barang</span>
                            <span class="detail-value">
                                <div id="detail-kode_barang"></div>
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Nama Barang</span>
                            <span class="detail-value">
                                <div id="detail-nama_barang"></div>
                            </span>
                        </div>
                    </div>

                    <!-- Kolom Kanan: QRCode -->
                    <div class="col-md-3 text-center">
                        <div id="detail-qrcode"></div>
                    </div>
                </div>

                <div class="detail-section">
                    <div class="section-title"><i class="bi bi-boxes"></i> Informasi Stok</div>
                    <div class="detail-item">
                        <span class="detail-label">Stok Awal</span>
                        <span class="detail-value">
                            <span class="badge bg-success badge-stok text-white">
                                <div id="detail-stok_awal"></div>
                                <div id="detail-satuan"></div>
                            </span>
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Stok Sekarang </span>
                        <span class="detail-value">
                            <div id="detail-tersedia"></div>
                        </span>
                    </div>
                </div>

                <div class="detail-section">
                    <div class="section-title"><i class="bi bi-shield-check"></i> Informasi Garansi</div>
                    <div class="detail-item">
                        <span class="detail-label">Tanggal Masuk</span>
                        <span class="detail-value">
                            <div id="detail-tanggal_mulai_garansi"></div>
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Tanggal Mulai Garansi</span>
                        <span class="detail-value">
                            <div id="detail-tanggal_mulai_garansi"></div>
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Lama Garansi</span>
                        <span class="detail-value">
                            <div id="detail-lama_garansi"></div>
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Sisa Garansi</span>
                        <span class="detail-value">
                            <div id="detail-rentang_garansi"></div>
                        </span>
                    </div>
                </div>

                <div class="detail-section">
                    <div class="section-title"><i class="bi bi-image"></i> Foto Barang</div>
                    <div class="text-center">
                        <div id="detail-foto_detail_barang"></div>
                    </div>
                </div>
            </div>
            <div class="detail-footer d-flex justify-content-end">
                <button type="button" class="btn btn-secondary btn-detail" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Generate kode barang otomatis
        function generateKodeBarang() {
            const prefix = 'BRG';
            const timestamp = new Date().getTime().toString().substr(-5);
            const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
            return `${prefix}${timestamp}${random}`;
        }

        // Set kode barang saat modal dibuka
        const modalCreate = document.getElementById('modal-create');
        modalCreate.addEventListener('show.bs.modal', function() {
            document.getElementById('create-kode_barang').value = generateKodeBarang();
        });

        // Generate kode baru saat tombol ditekan
        document.getElementById('generate-kode').addEventListener('click', function() {
            document.getElementById('create-kode_barang').value = generateKodeBarang();
        });

        // Preview foto barang
        document.getElementById('create-foto_detail_barang').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const errorDiv = this.closest('.form-group').querySelector('.error-msg');

            if (errorDiv) {
                errorDiv.remove();
            }

            if (file) {
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                const maxSize = 2 * 1024 * 1024;

                if (!allowedTypes.includes(file.type)) {
                    showError('create-foto_detail_barang', 'Format file harus JPEG, PNG, atau JPG!');
                    this.value = '';
                    document.getElementById('foto-preview').style.display = 'none';
                    return;
                }

                if (file.size > maxSize) {
                    showError('create-foto_detail_barang', 'Ukuran file maksimal 2MB!');
                    this.value = '';
                    document.getElementById('foto-preview').style.display = 'none';
                    return;
                }

                // Jika valid, tampilkan preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('foto-preview');
                    preview.style.display = 'block';
                    preview.querySelector('img').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        // Hapus foto preview
        document.getElementById('remove-foto').addEventListener('click', function() {
            document.getElementById('create-foto_detail_barang').value = '';
            document.getElementById('foto-preview').style.display = 'none';
        });

        const barcodeModal = document.getElementById('barcodeModal');
        barcodeModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const kodeBarang = button.getAttribute('data-kode');

            // Set kode barang di modal
            document.getElementById('barcode-kode-text').textContent = kodeBarang;

            // Hapus QRCode lama biar gak numpuk
            document.getElementById("qrcode").innerHTML = "";

            // Generate QRCode baru
            new QRCode(document.getElementById("qrcode"), {
                text: kodeBarang,
                width: 150,
                height: 150,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        });

        // Fungsi cetak barcode
        document.getElementById('print-barcode').addEventListener('click', function() {
            const printContent = `
                    <div style="text-align: center; padding: 20px;" >
                        <h3>Barcode Barang</h3>
                        <div style="display: flex; justify-content: center;">
                            ${document.getElementById('qrcode').innerHTML}
                        </div>
                        <p style="margin-top: 15px; font-weight: bold;">${document.getElementById('barcode-kode-text').textContent}</p>
                        <p>${new Date().toLocaleDateString('id-ID')}</p>
                    </div>
                `;

            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                    <html>
                        <head>
                            <title>Cetak Barcode</title>
                            <style>
                                body { font-family: Arial, sans-serif; }
                                @media print {
                                    @page { margin: 0; }
                                    body { margin: 1.6cm; }
                                }
                            </style>
                        </head>
                        <body onload="window.print(); window.close();">
                            ${printContent}
                        </body>
                    </html>
                `);
            printWindow.document.close();
        });
    });
</script>
