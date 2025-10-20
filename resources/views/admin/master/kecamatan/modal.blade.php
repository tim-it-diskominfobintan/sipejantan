<style>
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
</style>
<div class="modal fade" id="modal-create" tabindex="-1" aria-labelledby="modal-createLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-3">

            <!-- Header -->
            <div class="modal-header text-white"
                style="background: linear-gradient(90deg, #1e3c72, #2a5298) !important; border-top-left-radius: .5rem; border-top-right-radius: .5rem;">
                <h5 class="modal-title fw-bold" id="modal-createLabel">
                    <i class="bi bi-tag me-2"></i> Tambah {{ $title }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <!-- Body -->
            <form id="form-create">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">

                            <!-- Kode Kecamatan -->
                            <div class="form-group mb-3">
                                <label for="create-kode_kecamatan" class="form-label fw-semibold">
                                    <i class="bi bi-upc-scan text-primary me-1"></i> Kode Kecamatan
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-upc"></i></span>
                                    <input type="text" id="create-kode_kecamatan" name="kode_kecamatan"
                                        class="form-control" placeholder="Masukkan Kode Kecamatan">
                                </div>
                                <div id="create-kode_kecamatan-msg"></div>
                            </div>

                            <!-- Nama Kecamatan -->
                            <div class="form-group mb-3">
                                <label for="create-nama_kecamatan" class="form-label fw-semibold">
                                    <i class="bi bi-building text-success me-1"></i> Nama Kecamatan
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-building"></i></span>
                                    <input type="text" id="create-nama_kecamatan" name="nama_kecamatan"
                                        class="form-control" placeholder="Masukkan Nama Kecamatan">
                                </div>
                                <div id="create-nama_kecamatan-msg"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary-light px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-floppy me-2"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal modal-blur fade" id="modal-update" tabindex="-1" aria-labelledby="modal-updateLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-update">
                <div class="modal-header">
                    <h1 class="modal-title" id="modal-updateLabel"><i class="bi bi-tag me-2"></i>Ubah
                        {{ $title }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group mb-3">
                            <label for="update-kode_kecamatan" class="form-label fw-semibold"><i
                                    class="bi bi-upc-scan text-primary me-1"></i>Kode Kecamatan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-upc"></i></span>
                                <input type="text" id="update-kode_kecamatan" name="kode_kecamatan"
                                    class="form-control" placeholder="Masukkan Kode Kecamatan">
                            </div>
                            <div id="update-kode_kecamatan-msg"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="update-nama_kecamatan" class="form-label fw-semibold"><i
                                    class="bi bi-building text-success me-1"></i>Nama Kecamatan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-building"></i></span>
                                <input type="text" id="update-nama_kecamatan" name="nama_kecamatan"
                                    class="form-control" placeholder="Masukkan Nama Kecamatan">
                            </div>
                            <div id="update-nama_kecamatan-msg"></div>
                        </div>
                        <input type="hidden" class="form-control" id="update-id_kecamatan" name="id_kecamatan">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary-light" data-bs-dismiss="modal"> <i
                            class="bi bi-x-circle me-2"></i>Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-2"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
