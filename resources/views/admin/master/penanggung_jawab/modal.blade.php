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
                                <label class="form-label fw-semibold"><i class="bi bi-person me-1 text-primary"></i>Nama
                                    Penanggung
                                    Jawab</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" id="create-nama_penanggung_jawab" name="nama_penanggung_jawab"
                                        class="form-control" placeholder="Masukkan Nama Penanggung Jawab">
                                </div>
                                <div id="create-nama_penanggung_jawab-msg"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="create-warna_penanggung_jawab" class="form-label">Warna</label>
                                <input type="color" id="create-warna_penanggung_jawab" name="warna_penanggung_jawab"
                                    class="form-control" placeholder="Pilih warna">
                                <div id="create-warna_penanggung_jawab-msg"></div>
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
                    <i class="bi bi-archive me-2"></i> Tambah {{ $title }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form id="form-update">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label class="form-label fw-semibold"><i class="bi bi-person me-1 text-primary"></i>Nama
                                    Penanggung
                                    Jawab</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" id="update-nama_penanggung_jawab" name="nama_penanggung_jawab"
                                        class="form-control" placeholder="Masukkan Nama Penanggung Jawab">
                                </div>
                                <div id="update-nama_penanggung_jawab-msg"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="update-warna_penanggung_jawab" class="form-label">Warna</label>
                                <input type="color" id="update-warna_penanggung_jawab" name="warna_penanggung_jawab"
                                    class="form-control" placeholder="Pilih warna">
                                <div id="update-warna_penanggung_jawab-msg"></div>
                            </div>
                        </div>
                        <input type="hidden" class="form-control" id="update-id_penanggung_jawab"
                            name="id_penanggung_jawab">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary-light" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-floppy me-2"></i> Simpan
                    </button>
            </form>
        </div>
    </div>
</div>
