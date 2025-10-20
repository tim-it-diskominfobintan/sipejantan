
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
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label for="create-nama" class="form-label">Nama</label>
                                <input type="text" id="create-nama" name="nama" class="form-control"
                                    placeholder="Masukkan nama">
                                <div id="create-nama-msg"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="create-alias" class="form-label">Alias</label>
                                <input type="text" id="create-alias" name="alias" class="form-control"
                                    placeholder="Masukkan alias">
                                <small class="text-muted">Cth. diskominfo, disduk, dll.</small>
                                <div id="create-alias-msg"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="create-logo" class="form-label">Logo (rasio 1:1)</label>
                                <img src="{{ asset('assets/global/img/placeholder/img-placeholder-square.png') }}" class="rounded float-start mt-2 mb-3" alt="" id="create-logo-preview"
                                style="width: 100px; height: 100px; object-fit: cover;">
                                <input type="file" id="create-logo" name="logo" class="form-control"
                                    placeholder="Masukkan logo">
                                <small class="text-muted">Jika logo tidak dimasukkan, maka OPD otomatis akan menggunakan logo Kabupaten Bintan.</small>
                                <div id="create-logo-msg"></div>
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

<div class="modal modal-blur fade" id="modal-update" tabindex="-1" aria-labelledby="modal-updateLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-update">
                <div class="modal-header">
                    <h1 class="modal-title" id="modal-updateLabel">Ubah {{ $title }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label for="update-nama" class="form-label">Nama</label>
                                <input type="text" id="update-nama" name="nama" class="form-control"
                                    placeholder="Masukkan nama">
                                <div id="update-nama-msg"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="update-alias" class="form-label">Alias</label>
                                <input type="text" id="update-alias" name="alias" class="form-control"
                                    placeholder="Masukkan alias">
                                <small class="text-muted">Cth. diskominfo, disduk, dll.</small>
                                <div id="update-alias-msg"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="update-logo" class="form-label">Logo (rasio 1:1)</label>
                                <img src="{{ asset('assets/global/img/placeholder/img-placeholder-square.png') }}" class="rounded float-start mt-2 mb-3" alt="" id="update-logo-preview"
                                style="width: 100px; height: 100px; object-fit: cover;">
                                <input type="file" id="update-logo" name="logo" class="form-control"
                                    placeholder="Masukkan logo">
                                <small class="text-muted">Jika logo tidak dimasukkan, maka OPD otomatis akan menggunakan logo Kabupaten Bintan.</small>
                                <div id="update-logo-msg"></div>
                            </div>
                        </div>
                        <input type="hidden" class="form-control" id="update-id" name="id">
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

<div class="modal modal-blur fade" id="modal-sso" tabindex="-1" aria-labelledby="modal-ssoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-sso">
                <div class="modal-header">
                    <h1 class="modal-title" id="modal-ssoLabel">Cari {{ $title }} dari Bintan SSO</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label for="sso-opd" class="form-label">Nama OPD</label>
                                <select type="text" id="sso-opd" name="opds[]" class="form-select"
                                    placeholder="Masukkan mama OPD" multiple>
                                </select>
                                <div id="sso-opd-msg"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger-light" id="btn-authenticate-sso">Login SSO</button>

                    <div id="modal-sso-footer-action" style="display: none;">
                        <button type="button" class="btn btn-secondary-light me-2" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tambahkan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>