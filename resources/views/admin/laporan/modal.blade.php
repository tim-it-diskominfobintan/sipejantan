<div class="modal modal-blur fade" id="teknisiModal" tabindex="-1" aria-labelledby="teknisiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="form-update">
                <div class="modal-header">
                    <h1 class="modal-title" id="teknisiModalLabel"><i class="bi bi-person me-2"></i> Ubah Status
                        Laporan & Teknisi
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-section">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="update-status_laporan" class="form-label">Status Laporan</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-bell"></i></span>
                                        <select id="update-status_laporan" name="status_laporan" class="form-select">
                                            <option value="pending" disabled>Pending</option>
                                            <option value="proses">Proses</option>
                                            <option value="ditolak">Ditolak</option>
                                        </select>
                                    </div>
                                    <div id="update-status_laporan-msg"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="update-teknisi_id" class="form-label">Teknisi</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <select id="update-teknisi_id" name="teknisi_id" class="form-select">
                                            <option value="" selected disabled>Pilih Teknisi</option>
                                            @foreach ($teknisi as $item)
                                                <option value="{{ $item->id_teknisi }}">{{ $item->nama_teknisi }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="update-teknisi_id-msg"></div>
                                </div>
                            </div>
                            <div class="col-md-12" id="keterangan-tolak" style="display: none;">
                                <div class="form-group mb-3">
                                    <label for="update-ket_tolak" class="form-label">Keterangan Tolak</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-bell"></i></span>
                                        <input type="text" class="form-control" id="update-ket_tolak"
                                            name="ket_tolak" placeholder="Masukkan keterangan jika laporan ditolak">
                                    </div>
                                    <div id="update-ket_tolak-msg"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" class="form-control" id="update-id_laporan" name="id_laporan">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary-light" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-pencil me-2"></i> Ubah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
