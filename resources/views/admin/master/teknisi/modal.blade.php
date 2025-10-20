<style>
    /* Samakan tinggi & border select2 dengan input bootstrap */
    .select2-container .select2-selection--single {
        height: calc(2.25rem + 2px) !important;
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
</style>
<div class="modal fade" id="modal-create" tabindex="-1" aria-labelledby="modal-createLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-3">

            <!-- Header -->
            <div class="modal-header bg-gradient bg-primary text-white">
                <h5 class="modal-title fw-bold" id="modal-createLabel">
                    <i class="bi bi-tools me-2"></i> Tambah Teknisi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <!-- Form -->
            <form id="form-create">
                <div class="modal-body">
                    <div class="row g-3">

                        <!-- NIK Teknisi -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><i class="bi bi-credit-card me-1 text-primary"></i>
                                NIK Teknisi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-credit-card-2-front"></i></span>
                                <input type="number" name="nik_teknisi" class="form-control" id="create-nik_teknisi">
                            </div>
                            <div id="create-nik_teknisi-msg"></div>
                        </div>

                        <!-- Nama Teknisi -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><i class="bi bi-person-badge me-1 text-primary"></i>
                                Nama Teknisi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" name="nama_teknisi" class="form-control" id="create-nama_teknisi">
                            </div>
                            <div id="create-nama_teknisi-msg"></div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><i class="bi bi-envelope-at me-1 text-primary"></i>
                                Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email_teknisi" class="form-control"
                                    id="create-email_teknisi">
                            </div>
                            <div id="create-email_teknisi-msg"></div>
                        </div>

                        <!-- No. HP -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><i class="bi bi-phone me-1 text-primary"></i> No.
                                HP</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" name="hp_teknisi" class="form-control" id="create-hp_teknisi">
                            </div>
                            <div id="create-hp_teknisi-msg"></div>
                        </div>

                        <!-- Kecamatan -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><i class="bi bi-geo-alt me-1 text-primary"></i>
                                Kecamatan</label>
                            <select name="kecamatan_id" class="form-select select2" id="create-kecamatan_id">
                                <option value="">Pilih Kecamatan</option>
                                @foreach ($kecamatan as $item)
                                    <option value="{{ $item->id_kecamatan }}">{{ $item->nama_kecamatan }}</option>
                                @endforeach
                            </select>
                            <div id="create-kecamatan_id-msg"></div>
                        </div>

                        <!-- Kelurahan -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><i class="bi bi-geo me-1 text-primary"></i>
                                Kelurahan</label>
                            <select name="kelurahan_id" class="form-select select2" id="create-kelurahan_id" disabled>
                                <option value="">Pilih Kelurahan</option>
                            </select>
                            <div id="create-kelurahan_id-msg"></div>
                        </div>

                        <!-- Alamat -->
                        <div class="col-md-12">
                            <label class="form-label fw-semibold"><i class="bi bi-house-door me-1 text-primary"></i>
                                Alamat</label>
                            <textarea name="alamat_teknisi" class="form-control" rows="2" id="create-alamat_teknisi"></textarea>
                            <div id="create-alamat_teknisi-msg"></div>
                        </div>

                        <!-- Penanggung Jawab -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><i class="bi bi-person-check me-1 text-primary"></i>
                                Penanggung Jawab</label>
                            <select name="penanggung_jawab_id" class="form-select" id="create-penanggung_jawab_id">
                                <option value="">Pilih Penanggung Jawab</option>
                                @foreach ($penanggungjawab as $item)
                                    <option value="{{ $item->id_penanggung_jawab }}">{{ $item->nama_penanggung_jawab }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="create-penanggung_jawab_id-msg"></div>
                        </div>

                        <!-- Foto -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><i class="bi bi-image me-1 text-primary"></i> Foto
                                Teknisi</label>
                            <input type="file" name="foto_teknisi" class="form-control" accept="image/*"
                                id="create-foto_teknisi">
                            <div id="create-foto_teknisi-msg"></div>
                        </div>

                        <!-- Username -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><i
                                    class="bi bi-person-circle me-1 text-primary"></i> Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-circle"></i></span>
                                <input type="text" name="username" class="form-control" id="create-username">
                            </div>
                            <div id="create-username-msg"></div>
                        </div>

                        <!-- Password -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><i class="bi bi-key me-1 text-primary"></i>
                                Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input type="password" name="password" class="form-control" id="create-password">
                            </div>
                            <div id="create-password-msg"></div>
                        </div>

                    </div>
                </div>

                <!-- Footer -->
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

<div class="modal fade" id="modal-update" tabindex="-1" aria-labelledby="modal-updateLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modal-updateLabel"><i class="bi bi-tools me-2"></i> Ubah Teknisi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <form id="form-update">
                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><i class="bi bi-credit-card me-1 text-primary"></i>
                                NIK Teknisi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-credit-card-2-front"></i></span>
                                <input type="number" name="nik_teknisi" class="form-control"
                                    id="update-nik_teknisi">
                            </div>
                            <div id="update-nik_teknisi-msg"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><i class="bi bi-person-badge me-1 text-primary"></i>
                                Nama Teknisi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" name="nama_teknisi" class="form-control"
                                    id="update-nama_teknisi">
                            </div>
                            <div id="update-nama_teknisi-msg"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><i class="bi bi-envelope-at me-1 text-primary"></i>
                                Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email_teknisi" class="form-control"
                                    id="update-email_teknisi">
                            </div>
                            <div id="update-email-msg"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><i class="bi bi-phone me-1 text-primary"></i> No.
                                HP</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" name="hp_teknisi" class="form-control" id="update-hp_teknisi">
                            </div>
                            <div id="update-hp_teknisi-msg"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><i class="bi bi-geo-alt me-1 text-primary"></i>
                                Kecamatan</label>
                            <select name="kecamatan_id" class="form-select" id="update-kecamatan_id">
                                <option value="">Pilih Kecamatan</option>
                                @foreach ($kecamatan as $item)
                                    <option value="{{ $item->id_kecamatan }}">{{ $item->nama_kecamatan }}</option>
                                @endforeach
                            </select>
                            <div id="update-kecamatan_id-msg"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><i class="bi bi-geo me-1 text-primary"></i>
                                Kelurahan</label>
                            <select name="kelurahan_id" class="form-select" id="update-kelurahan_id" disabled>
                                <option value="">Pilih Kelurahan</option>
                            </select>
                            <div id="update-kelurahan_id-msg"></div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold"><i class="bi bi-house-door me-1 text-primary"></i>
                                Alamat</label>
                            <textarea name="alamat_teknisi" class="form-control" rows="2" id="update-alamat_teknisi"></textarea>
                            <div id="update-alamat_teknisi-msg"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><i class="bi bi-person-check me-1 text-primary"></i>
                                Penanggung Jawab</label>
                            <select name="penanggung_jawab_id" class="form-select" id="update-penanggung_jawab_id">
                                <option value="">Pilih Penanggung Jawab</option>
                                @foreach ($penanggungjawab as $item)
                                    <option value="{{ $item->id_penanggung_jawab }}">
                                        {{ $item->nama_penanggung_jawab }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="update-penanggung_jawab_id-msg"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><i class="bi bi-image me-1 text-primary"></i> Foto
                                Teknisi</label>
                            <input type="file" name="foto_teknisi" class="form-control" accept="image/*">
                            <div id="update-foto_teknisi-msg"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><i
                                    class="bi bi-person-circle me-1 text-primary"></i> Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-circle"></i></span>
                                <input type="text" name="username" class="form-control" id="update-username">
                            </div>
                            <div id="update-username-msg"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><i class="bi bi-key me-1 text-primary"></i>
                                Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <div id="update-password-msg"></div>
                        </div>

                    </div>
                </div>
                <input type="hidden" class="form-control" id="update-id_teknisi" name="id_teknisi">
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
