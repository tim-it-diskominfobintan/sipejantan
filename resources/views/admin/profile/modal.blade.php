<!-- Modal -->
<div class="modal fade" id="modal-update_photo" tabindex="-1" aria-labelledby="modal-update_photoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-update_photo">
                <div class="modal-header">
                    <h1 class="modal-title" id="modal-update_fotoLabel">Ubah Foto Profil</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="update-photo_profile">Foto profil (rasio 1:1)</label>
                        <img src="{{ asset('assets/global/img/placeholder/img-placeholder-circle.png') }}"
                            class="rounded-pill float-start mt-2 mb-3" alt="" id="update-photo_profile-preview"
                            style="width: 100px; height: 100px; object-fit: cover;">
                        <input type="file" name="photo_profile" id="update-photo_profile" class="form-control">
                        <div id="update-photo_profile-msg"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Ubah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-update_profil" tabindex="-1" aria-labelledby="modal-update_profilLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-update_profil">
                <div class="modal-header">
                    <h1 class="modal-title" id="modal-update_fotoLabel">Ubah Profil Saya</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="update_profil-name">Nama</label>
                        <input type="text" name="name" id="update_profil-name" class="form-control"
                            placeholder="Masukkan nama" value="{{ auth()->user()->name }}">
                        <div id="update_profil-name-msg"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="update_profil-name">Email</label>
                        <input type="email" name="email" id="update_profil-email" class="form-control"
                            placeholder="Masukkan email" value="{{ auth()->user()->email }}">
                        <div id="update_profil-email-msg"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Ubah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-update_password" tabindex="-1" aria-labelledby="modal-update_passwordLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-update_password">
                <div class="modal-header">
                    <h1 class="modal-title" id="modal-update_passwordLabel">Ganti password</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="update_password-old_password" class="form-label">Password saat ini</label>
                        <div class="input-group input-group-flat">
                            <input type="password" id="update_password-old_password" class="form-control"
                                name="old_password" placeholder="Masukkan password saat ini" autocomplete="off">
                            <span class="input-group-text">
                                <a href="#" id="toggle-password" class="link-secondary toggle-password"
                                    data-bs-toggle="tooltip" aria-label="Tampilkan password saat ini"
                                    data-bs-original-title="Tampilkan password saat ini">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </span>
                        </div>
                        <div id="update_password-old_password-msg"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="update_password-new_password" class="form-label">Password baru</label>
                        <div class="input-group input-group-flat">
                            <input type="password" id="update_password-new_password" class="form-control"
                                name="new_password" placeholder="Masukkan password baru" autocomplete="off">
                            <span class="input-group-text">
                                <a href="#" id="toggle-password" class="link-secondary toggle-password"
                                    data-bs-toggle="tooltip" aria-label="Tampilkan password baru"
                                    data-bs-original-title="Tampilkan password baru">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </span>
                        </div>
                        <div id="update_password-new_password-msg"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="update_password-confirm_new_password" class="form-label">Konfirmasi password
                            baru</label>
                        <div class="input-group input-group-flat">
                            <input type="password" id="update_password-confirm_new_password" class="form-control"
                                name="confirm_new_password" placeholder="Konfirmasi password baru"
                                autocomplete="off">
                            <span class="input-group-text">
                                <a href="#" id="toggle-password" class="link-secondary toggle-password"
                                    data-bs-toggle="tooltip" aria-label="Tampilkan konfirmasi password baru"
                                    data-bs-original-title="Tampilkan konfirmasi password baru">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </span>
                        </div>
                        <div id="update_password-confirm_new_password-msg"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
