<div class="modal modal-blur fade" id="modal-create" tabindex="-1" aria-labelledby="modal-createLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-3">

            <!-- Header -->
            <div class="modal-header bg-gradient bg-primary text-white">
                <h5 class="modal-title fw-bold" id="modal-createLabel">
                    <i class="bi bi-archive me-2"></i> Tambah {{ $title }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <form id="form-create">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label class="form-label fw-semibold"><i class="bi bi-archive me-1 text-primary"></i>
                                    Jenis Asset</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-archive"></i></span>
                                    <input type="text" id="create-jenis_asset" name="jenis_asset"
                                        class="form-control" placeholder="Masukkan Jenis Asset">
                                </div>
                                <div id="create-jenis_asset-msg"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label fw-semibold"><i class="bi bi-image me-1 text-primary"></i> Foto
                                    Jenis Asset</label>
                                <input type="file" name="foto_jenis_asset" class="form-control" accept="image/*">
                                <div id="create-foto_jenis_asset-msg"></div>
                            </div>
                        </div>
                    </div>
                </div>
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

<div class="modal modal-blur fade" id="modal-update" tabindex="-1" aria-labelledby="modal-updateLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-3">

            <!-- Header -->
            <div class="modal-header bg-gradient bg-primary text-white">
                <h5 class="modal-title fw-bold" id="modal-createLabel">
                    <i class="bi bi-archive me-2"></i> Ubah {{ $title }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form id="form-update">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold"><i class="bi bi-archive me-1 text-primary"></i>
                                Jenis Asset</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-archive"></i></span>
                                <input type="text" id="update-jenis_asset" name="jenis_asset" class="form-control"
                                    placeholder="Masukkan Jenis Asset">
                            </div>
                            <div id="update-jenis_asset-msg"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold"><i class="bi bi-image me-1 text-primary"></i> Foto
                                Jenis Asset</label>
                            <input type="file" name="foto_jenis_asset" class="form-control" accept="image/*">
                            <div id="update-foto_jenis_asset-msg"></div>
                        </div>
                        <input type="hidden" class="form-control" id="update-id_jenis_asset" name="id_jenis_asset">
                    </div>
                </div>
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
