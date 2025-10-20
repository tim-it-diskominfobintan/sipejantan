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
                        <input type="text" id="create-auth_provider" name="auth_provider" class="form-control"
                            value="self" placeholder="_auth_provider" hidden>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="create-username" class="form-label">Username</label>
                                <input type="text" id="create-username" name="username" class="form-control"
                                    placeholder="Masukkan username">
                                <div id="create-username-msg"></div>
                                <small class="text-muted">Username akan menjadi <strong>identifier</strong> untuk
                                    login.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="create-name" class="form-label">Nama</label>
                                <input type="text" id="create-name" name="name" class="form-control"
                                    placeholder="Masukkan name">
                                <div id="create-name-msg"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="create-email" class="form-label">Email</label>
                                <input type="text" id="create-email" name="email" class="form-control"
                                    placeholder="Masukkan email">
                                <div id="create-email-msg"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="create-photo_profile" class="form-label">Foto profil (rasio 1:1)</label>
                                <img src="{{ asset('assets/global/img/placeholder/img-placeholder-circle.png') }}"
                                    class="rounded-pill float-start mt-2 mb-3" alt=""
                                    id="create-photo_profile-preview"
                                    style="width: 100px; height: 100px; object-fit: cover;">
                                <input type="file" id="create-photo_profile" name="photo_profile"
                                    class="form-control" accept="image/*">
                                <div id="create-photo_profile-msg"></div>
                            </div>
                            {{-- <div class="form-group mb-3">
                                <label for="create-photo_profile" class="form-label">Foto profil</label>
                                <input type="file" id="create-photo_profile" name="photo_profile"
                                    class="form-control" accept="image/*">
                                <div id="create-photo_profile-msg"></div>
                            </div> --}}
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="create-password" class="form-label">Password</label>
                                <div class="input-group input-group-flat">
                                    <input type="password" id="create-password" class="form-control" name="password"
                                        placeholder="Masukkan password" autocomplete="off">
                                    <span class="input-group-text">
                                        <a href="#" id="toggle-password" class="link-secondary"
                                            data-bs-toggle="tooltip" aria-label="Show password"
                                            data-bs-original-title="Show password">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </span>
                                </div>
                                <div id="create-password-msg"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="create-role_id" class="form-label">Role</label>
                                <select name="role_id" id="create-role_id" class="form-select">
                                    <option value="" selected disabled>Pilih</option>
                                    @foreach ($roles as $d)
                                        @if ($d->id >= 0 && $d->id <= 2)
                                            <option value="{{ $d->id }}">{{ $d->name }} -
                                                {{ $d->description }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <div id="create-role_id-msg"></div>
                            </div>
                        </div>
                    </div>

                    <section id="form-section-admin_opd">
                        <hr>

                        <h3 id="title-form-section-admin_opd">Form Admin OPD</h3>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="create-opd_ids" class="form-label">Opd</label>
                                <select name="opd_ids[]" id="create-opd_ids" class="form-select" multiple>
                                    <option value="" selected disabled>Pilih</option>
                                    @foreach ($opds as $d)
                                        <option value="{{ $d->id }}">{{ $d->nama }}</option>
                                    @endforeach
                                </select>
                                <div id="create-opd_ids-msg"></div>
                            </div>
                        </div>
                    </section>

                    <input type="text" id="create-status" name="status" class="form-control" value="active"
                        hidden>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambahkan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal modal-blur fade" id="modal-create_sso" tabindex="-1" aria-labelledby="modal-create_ssoLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-create_sso">
                <div class="modal-header">
                    <h1 class="modal-title" id="modal-create_ssoLabel">Tambah {{ $title }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" id="create_sso-auth_provider" name="auth_provider" class="form-control"
                        value="bintan-sso" placeholder="_auth_provider" hidden>
                    <input type="text" id="create_sso-auth_provider_user_id" name="auth_provider_user_id"
                        class="form-control" placeholder="auth_provider_user_id" hidden>

                    <div class="row mb-3">
                        <div class="col-md-8 offset-md-2">
                            <div class="form-group mb-3">
                                <label for="create_sso-search_username" class="form-label">NIP/NIK dari SSO</label>
                                <input type="text" id="create_sso-search_username" name="auth_provider_id"
                                    class="form-control" placeholder="Masukkan username">
                                <div id="create_sso-search_username-msg"></div>
                                <small class="text-muted">Cari menggunakan NIP/NIK.</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="create_sso-username" class="form-label">Username</label>
                                <input type="text" id="create_sso-username" name="username" class="form-control"
                                    placeholder="Masukkan username">
                                <div id="create_sso-username-msg"></div>
                                <small class="text-muted">Username akan menjadi <strong>identifier</strong> untuk
                                    login.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="create_sso-name" class="form-label">Nama</label>
                                <input type="text" id="create_sso-name" name="name" class="form-control"
                                    placeholder="Masukkan name">
                                <div id="create_sso-name-msg"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="create_sso-email" class="form-label">Email</label>
                                <input type="text" id="create_sso-email" name="email" class="form-control"
                                    placeholder="Masukkan email">
                                <div id="create_sso-email-msg"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="create_sso-photo_profile" class="form-label">Foto profil (rasio
                                    1:1)</label>
                                <img src="{{ asset('assets/global/img/placeholder/img-placeholder-circle.png') }}"
                                    class="rounded-pill float-start mt-2 mb-3" alt=""
                                    id="create_sso-photo_profile-preview"
                                    style="width: 100px; height: 100px; object-fit: cover;">
                                <input type="file" id="create_sso-photo_profile" name="photo_profile"
                                    class="form-control" accept="image/*">
                                <input type="text" id="create_sso-photo_profile_url" name="photo_profile_url"
                                    class="form-control" placeholder="_photo_profile_url" hidden>
                                <div id="create_sso-photo_profile-msg"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="create_sso-role_id" class="form-label">Role</label>
                                <select name="role_id" id="create_sso-role_id" class="form-select">
                                    <option value="" selected disabled>Pilih</option>
                                    @foreach ($roles as $d)
                                        <option value="{{ $d->id }}">{{ $d->name }} -
                                            {{ $d->description }}</option>
                                    @endforeach
                                </select>
                                <div id="create_sso-role_id-msg"></div>
                            </div>
                        </div>
                    </div>

                    <section id="form-section-sso_asn_nonasn">
                        <hr>

                        <h3 id="title-form-section-sso_asn_nonasn">Form ASN</h3>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="create_sso-opd_ids" class="form-label">Opd</label>
                                    <select name="opd_ids[]" id="create_sso-opd_ids" class="form-select" multiple>
                                        <option value="" selected disabled>Pilih</option>
                                        @foreach ($opds as $d)
                                            <option value="{{ $d->id }}">{{ $d->nama }}</option>
                                        @endforeach
                                    </select>
                                    <div id="create_sso-opd_ids-msg"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="create_sso-nik" class="form-label">NIK</label>
                                    <input type="text" id="create_sso-nik" name="nik" class="form-control"
                                        placeholder="Masukkan nik">
                                    <div id="create_sso-nik-msg"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="create_sso-nip" class="form-label">NIP</label>
                                    <input type="text" id="create_sso-nip" name="nip" class="form-control"
                                        placeholder="Masukkan nip">
                                    <div id="create_sso-nip-msg"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="create_sso-no_hp" class="form-label">No HP</label>
                                    <input type="text" id="create_sso-no_hp" name="no_hp" class="form-control"
                                        placeholder="Masukkan No. HP">
                                    <div id="create_sso-no_hp-msg"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="create_sso-alamat" class="form-label">Alamat</label>
                                    <input type="text" id="create_sso-alamat" name="alamat" class="form-control"
                                        placeholder="Masukkan No. HP">
                                    <div id="create_sso-alamat-msg"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="create_sso-tempat_lahir" class="form-label">Tempat lahir</label>
                                    <input type="text" id="create_sso-tempat_lahir" name="tempat_lahir"
                                        class="form-control" placeholder="Masukkan No. HP">
                                    <div id="create_sso-tempat_lahir-msg"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="create_sso-jenis_kelamin" class="form-label">Jenis kelamin</label>
                                    <select name="jenis_kelamin" id="create_sso-jenis_kelamin" class="form-select">
                                        <option value="" selected disabled>Pilih</option>
                                        <option value="laki">Laki-laki</option>
                                        <option value="perempuan">Perempuan</option>
                                    </select>
                                    <div id="create_sso-jenis_kelamin-msg"></div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <input type="text" id="create_sso-status" name="status" class="form-control" value="active"
                        hidden>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambahkan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal modal-blur fade" id="modal-update_lock_status" tabindex="-1"
    aria-labelledby="modal-update_lock_statusLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-update_lock_status">
                <div class="modal-header">
                    <h1 class="modal-title" id="modal-update_lock_statusLabel">Ubah status {{ $title }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="update_lock_status-status" class="form-label">Status</label>
                                <select name="status" id="update_lock_status-status" class="form-select">
                                    <option value="" selected disabled>Pilih</option>
                                    <option value="active">Aktif</option>
                                    <option value="inactive">Tidak aktif</option>
                                    <option value="locked">Kunci akun</option>
                                </select>
                                <div id="update_lock_status-status-msg"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="update_lock_status-status_description" class="form-label">Alasan</label>
                                <input type="text" id="update_lock_status-status_description"
                                    name="status_description" class="form-control" placeholder="Alasan/deskripsi">
                                <div id="update_lock_status-status_description-msg"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" class="form-control" id="update_lock_status-id" name="id">

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Ubah</button>
                </div>
            </form>
        </div>
    </div>
</div>
