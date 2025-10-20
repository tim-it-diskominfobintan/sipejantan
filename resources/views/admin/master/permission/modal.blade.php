<div class="modal modal-blur fade" id="modal-create" tabindex="-1" aria-labelledby="modal-createLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-create">
                <div class="modal-header">
                    <h1 class="modal-title" id="modal-createLabel">Tambah {{ $title }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="create-name" class="form-label">Nama</label>
                                <input type="text" id="create-name" name="name" class="form-control"
                                    placeholder="Masukkan nama">
                                <div id="create-name-msg"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="create-guard_name" class="form-label">Guard</label>
                                <input type="text" id="create-guard_name" name="guard_name" class="form-control"
                                    placeholder="Masukkan guard" value="web">
                                <div id="create-guard_name-msg"></div>
                                <small class="text-muted">Ganti guard jika dibutuhkan.</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambahkan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal modal-blur fade" id="modal-update" tabindex="-1" aria-labelledby="modal-updateLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-update">
                <div class="modal-header">
                    <h1 class="modal-title" id="modal-updateLabel">Tambah {{ $title }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="update-name" class="form-label">Nama</label>
                                <input type="text" id="update-name" name="name" class="form-control"
                                    placeholder="Masukkan nama">
                                <div id="update-name-msg"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="update-guard_name" class="form-label">Guard</label>
                                <input type="text" id="update-guard_name" name="guard_name" class="form-control"
                                    placeholder="Masukkan guard" value="web">
                                <div id="update-guard_name-msg"></div>
                                <small class="text-muted">Ganti guard jika dibutuhkan.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" class="form-control" id="update-id" name="id">

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Ubah</button>
                </div>
            </form>
        </div>
    </div>
</div>

