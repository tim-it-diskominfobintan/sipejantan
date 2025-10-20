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
</style>
<div class="modal modal-blur fade" id="modal-create" tabindex="-1" aria-labelledby="modal-createLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-create">
                <div class="modal-header">
                    <h1 class="modal-title" id="modal-createLabel"><i class="bi bi-tag me-2"></i>Tambah
                        {{ $title }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label for="create-kode_kelurahan" class="form-label fw-semibold"><i
                                        class="bi bi-upc-scan text-primary me-1"></i>Kode Kelurahan</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-upc"></i></span>
                                    <input type="text" id="create-kode_kelurahan" name="kode_kelurahan"
                                        class="form-control" placeholder="Masukkan Kode Kelurahan">
                                </div>
                                <div id="create-kode_kelurahan-msg"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="create-nama_kelurahan" class="form-label fw-semibold"><i
                                        class="bi bi-building text-success me-1"></i>Nama Kelurahan</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-building"></i></span>
                                    <input type="text" id="create-nama_kelurahan" name="nama_kelurahan"
                                        class="form-control" placeholder="Masukkan Nama Kelurahan">
                                </div>
                                <div id="create-nama_kelurahan-msg"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="create-kecamatan_id" class="form-label fw-semibold"><i
                                        class="bi bi-building text-success me-1"></i>Nama Kecamatan</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-building"></i></span>
                                    <select name="kecamatan_id" id="create-kecamatan_id" class="form-control">
                                        <option value="" disabled selected>Pilih Kecamatan</option>
                                        @foreach ($kecamatan as $item)
                                            <option value="{{ $item->id_kecamatan }}">{{ $item->nama_kecamatan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="create-kecamatan_id-msg"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary-light" data-bs-dismiss="modal"><i
                            class="bi bi-x-circle me-2"></i>Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-2"></i>Simpan</button>
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
                        <div class="form-group mb-3">
                            <label for="update-kode_kelurahan" class="form-label fw-semibold"><i
                                    class="bi bi-upc-scan text-primary me-1"></i>Kode Kelurahan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-upc"></i></span>
                                <input type="text" id="update-kode_kelurahan" name="kode_kelurahan"
                                    class="form-control" placeholder="Masukkan Kode Kelurahan">
                            </div>
                            <div id="update-kode_kelurahan-msg"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="update-nama_kelurahan" class="form-label fw-semibold"><i
                                    class="bi bi-building text-success me-1"></i>Nama Kelurahan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-building"></i></span>
                                <input type="text" id="update-nama_kelurahan" name="nama_kelurahan"
                                    class="form-control" placeholder="Masukkan Nama Kelurahan">
                            </div>
                            <div id="update-nama_kelurahan-msg"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="update-kecamatan_id" class="form-label fw-semibold"><i
                                    class="bi bi-building text-success me-1"></i>Nama Kecamatan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-building"></i></span>
                                <select name="kecamatan_id" id="update-kecamatan_id" class="form-control">
                                    <option value="" disabled selected>Pilih Kecamatan</option>
                                    @foreach ($kecamatan as $item)
                                        <option value="{{ $item->id_kecamatan }}">{{ $item->nama_kecamatan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="update-kecamatan_id-msg"></div>
                        </div>
                        <input type="hidden" class="form-control" id="update-id_kelurahan" name="id_kelurahan">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary-light" data-bs-dismiss="modal"><i
                            class="bi bi-x-circle me-2"></i>Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-2"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
