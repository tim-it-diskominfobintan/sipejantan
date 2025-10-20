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

                        <div class="form-group mb-3 col-6">
                            <label for="create-barang_id" class="form-label">Nama Barang</label>
                            <select name="barang_id" id="create-barang_id" class="form-select">
                                <option value="" disabled selected>Pilih Barang</option>
                                @foreach ($barang as $item)
                                    <option value="{{ $item->id_barang }}">
                                        {{ $item->nama_barang }}</option>
                                @endforeach
                            </select>
                            <div id="create-barang_id-msg"></div>
                        </div>

                        <div class="form-group mb-3 col-6">
                            <label for="create-tanggal_opname" class="form-label">Tanggal Opname</label>
                            <input type="date" id="create-tanggal_opname" name="tanggal_opname" class="form-control">
                            <div id="create-tanggal_opname-msg"></div>
                        </div>

                        <div class="form-group mb-3 col-6">
                            <label for="create-jenis_opname" class="form-label">Jenis Opname</label>
                            <select name="jenis_opname" id="create-jenis_opname" class="form-select">
                                <option value="" disabled selected>Pilih Jenis Opname</option>
                                <option value="masuk">Masuk</option>
                                <option value="keluar">Keluar</option>
                            </select>
                            <div id="create-jenis_opname-msg"></div>
                        </div>

                        <div class="form-group mb-3 col-6">
                            <label for="create-jumlah_opname" class="form-label">Jumlah Opname</label>
                            <input type="number" id="create-jumlah_opname" name="jumlah_opname" class="form-control"
                                min="0">
                            <div id="create-jumlah_opname-msg"></div>
                        </div>

                        <div class="form-group mb-3 col-6" id="container-no_bukti">
                            <label for="create-no_bukti" class="form-label">No Bukti / ID Perbaikan</label>
                            <!-- Default: text input -->
                            <input type="text" id="create-no_bukti" name="no_bukti" class="form-control"
                                placeholder="No Bukti">
                            <div id="create-no_bukti-msg"></div>
                        </div>

                        <div class="form-group mb-3 col-6">
                            <label for="create-keterangan" class="form-label">Keterangan</label>
                            <input type="text" id="create-keterangan" name="keterangan" class="form-control">
                            <div id="create-keterangan-msg"></div>
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
                    <h1 class="modal-title" id="modal-updateLabel">Tambah {{ $title }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="form-group mb-3 col-6">
                            <label for="update-barang_id" class="form-label">Nama Barang</label>
                            <select name="barang_id" id="update-barang_id" class="form-select">
                                <option value="" disabled selected>Pilih Barang</option>
                                @foreach ($barang as $item)
                                    <option value="{{ $item->id_barang }}">
                                        {{ $item->nama_barang }}</option>
                                @endforeach
                            </select>
                            <div id="update-barang_id-msg"></div>
                        </div>

                        <div class="form-group mb-3 col-6">
                            <label for="update-tanggal_opname" class="form-label">Tanggal Opname</label>
                            <input type="date" id="update-tanggal_opname" name="tanggal_opname"
                                class="form-control">
                            <div id="update-tanggal_opname-msg"></div>
                        </div>

                        <div class="form-group mb-3 col-6">
                            <label for="update-jenis_opname" class="form-label">Jenis Opname</label>
                            <select name="jenis_opname" id="update-jenis_opname" class="form-select">
                                <option value="" disabled selected>Pilih Jenis Opname</option>
                                <option value="masuk">Masuk</option>
                                <option value="keluar">Keluar</option>
                            </select>
                            <div id="update-jenis_opname-msg"></div>
                        </div>

                        <div class="form-group mb-3 col-6">
                            <label for="update-jumlah_opname" class="form-label">Jumlah Opname</label>
                            <input type="number" id="update-jumlah_opname" name="jumlah_opname"
                                class="form-control" min="0">
                            <div id="update-jumlah_opname-msg"></div>
                        </div>

                        <div class="form-group mb-3 col-6" id="container-no_bukti">
                            <label for="update-no_bukti" class="form-label">No Bukti / ID Perbaikan</label>
                            <!-- Default: text input -->
                            <input type="text" id="update-no_bukti" name="no_bukti" class="form-control"
                                placeholder="No Bukti">
                            <div id="update-no_bukti-msg"></div>
                        </div>

                        <div class="form-group mb-3 col-6">
                            <label for="update-keterangan" class="form-label">Keterangan</label>
                            <input type="text" id="update-keterangan" name="keterangan" class="form-control">
                            <div id="update-keterangan-msg"></div>
                        </div>
                        <input type="hidden" class="form-control" id="update-id_opname" name="id_opname">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Ubah</button>
                </div>
            </form>
        </div>
    </div>
</div>
