@extends('layouts.admin.main')

@section('action-header')
    {{-- UNTUK TOMBOL TAMBAH DATA DLL, KALO GAADA KOSONGIN AJA --}}
    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            <button type="button" class="btn btn-4" data-bs-toggle="modal" data-bs-target="#modal-create_sso">
                <i class="bi bi-plus-lg me-2"></i> Tambah {{ $title }} dari SSO
            </button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-create">
                <i class="bi bi-plus-lg me-2"></i> Tambah {{ $title }}
            </button>
        </div>
    </div>
@endsection

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">List {{ $title }}</h3>
                </div>
                <div class="card-body py-0 px-0 mx-0">
                    <div class="table-responsive table-full-to-card-body py-3">
                        <table class="table" id="table">
                            <thead>
                                <tr class="bg-body-tertiary">
                                    <th width="20px">No</th>
                                    <th>Akun</th>
                                    <th width="200px">Status</th>
                                    <th width="200px">Role</th>
                                    <th>Terakhir login</th>
                                    <th>Bergabung pada</th>
                                    <th>Auth providers</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.master.user.modal')
@endsection

@push('script')
    <script>
        let formMode = 'create'

        $('#form-section-admin_opd').hide()
        $('#form-section-sso_asn_nonasn').hide()

        const table = $('#table').DataTable({
            serverSide: true,
            columnDefs: [{
                    targets: [0, -1],
                    className: "text-center",
                    responsivePriority: 1,
                }, {
                    targets: [-1, -3],
                    className: "w-50",
                    responsivePriority: 1,
                },
                {
                    targets: [2],
                    className: "text-center",
                }
            ],
            ajax: {
                url: "{{ url()->current() }}",
                method: 'GET',
                error: function(response) {
                    handleAjaxJqueryError(response, {
                        formPrefix: formMode,
                        msgSuffix: 'msg'
                    })
                }
            },
            lengthMenu: datatablesDefaultConfig().lengthMenu,
            pageLength: datatablesDefaultConfig().pageLength,
            language: datatablesDefaultConfig().language,
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'username',
                    name: 'username'
                },
                {
                    data: 'status',
                    name: 'status',
                },
                {
                    data: 'role',
                    name: 'role',
                },
                {
                    data: 'last_login_at',
                    name: 'last_login_at',
                },
                {
                    data: 'joined_at',
                    name: 'joined_at',
                },
                {
                    data: 'auth_provider',
                    name: 'auth_provider',
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $('#create-photo_profile').change(function() {
            previewImageFromInput(this, '#create-photo_profile-preview');
        });

        $('#create_sso-photo_profile').change(function() {
            previewImageFromInput(this, '#create_sso-photo_profile-preview');
        });

        $('#create-role_id').change(function() {
            if ($(this).val() == '2') {
                $('#form-section-admin_opd').show()
            } else {
                $('#form-section-admin_opd').hide()
                $('#create-opd_ids').val('').trigger('change')
            }
        })

        $('#toggle-password').click(function() {
            const attribute = $('input[name="password"]').attr('type')
            $('input[name="password"]').attr('type', (attribute === 'text' ? 'password' : 'text'))
        })

        $('#form-create').submit(function(e) {
            e.preventDefault()

            formMode = 'create'

            const formData = new FormData(this)
            formData.append('_token', getCsrfToken())
            formData.append('_method', 'post')

            callApi(formData)
        })

        $('#form-create_sso').submit(function(e) {
            e.preventDefault()

            formMode = 'create_sso'

            const formData = new FormData(this)
            formData.append('_token', getCsrfToken())
            formData.append('_method', 'post')

            callApi(formData)
        })

        $('#form-update').submit(function(e) {
            e.preventDefault()

            formMode = 'update'

            const formData = new FormData(this)
            formData.append('_token', getCsrfToken())
            formData.append('_method', 'put')

            callApi(formData, `{{ url()->current() }}/${$('#update-id').val()}`)
        })

        $('#modal-create_sso').click(function() {
            $('#create_sso').modal('show')

            const ssoToken = `{{ session()->get('bintan-sso.access_token') }}`

            if (ssoToken == '') {
                $('#create_sso-footer-action').hide()
                $('#btn-authenticate-sso').show()
            } else {
                $('#create_sso-footer-action').show()
                $('#btn-authenticate-sso').hide()
            }

            // $('#create_sso-username').val('').trigger('change')
            // $('#create_sso-username').trigger('click')
        })

        initializeSelect2('#create_sso-opd_ids', '#modal-create_sso')
        initializeSelect2('#create-opd_ids', '#modal-create')

        $('#create_sso-search_username').select2({
            language: 'id',
            dropdownParent: '#modal-create_sso',
            placeholder: 'Cari NIK/NIP...',
            theme: 'bootstrap-5',
            width: '100%',
            allowClear: true,
            minimumInputLength: 2,
            ajax: {
                url: "{{ url('/admin/master/user/sso') }}",
                dataType: 'json',
                method: 'GET',
                delay: 250,
                data: function(params) {
                    return {
                        keyword: params.term,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    };
                },
                error: function(response) {
                    handleAjaxJqueryError(response, {
                        onUnauthorized: function() {
                            showSwal('Anda belum terautentikasi oleh Bintan SSO', 'error')
                        }
                    })
                },
                processResults: function(response) {
                    if (response.error) {
                        return {
                            results: []
                        };
                    }

                    const results = response.data.map(function(data) {
                        let nama = ''
                        let opd = ''
                        if (data.role == 'opd') {
                            nama = data.opd.length >= 1 ? data.opd[0].nama : ''
                        } else if (data.role == 'admin') {
                            nama = data.username
                        } else if (data.role == 'asn' || data.role == 'nonasn') {
                            nama = data.profile.nama
                        }

                        opd = data.opd.length >= 1 ? data.opd.map(opd => opd.nama).join(', ') : 'Tidak ada OPD'

                        return {
                            id: data.id,
                            text: `${data.username} ${nama != '' ? `- ${nama}` : ''} - ${opd}`,
                            raw: data
                        };
                    });
                    return {
                        results: results
                    };
                },
                cache: true
            }
        });

        $('#create_sso-search_username').on('select2:select', function(e) {
            const data = e.params.data.raw

            $('#create_sso-search_username').data('select2').$container.find('.select2-selection__placeholder')
                .text(e.params.data.text);

            if (data.role == 'asn' || data.role == 'nonasn') {
                $('#form-section-sso_asn_nonasn').show()

                if (data.role == 'asn') $('#title-form-section-sso_asn_nonasn').text('Form ASN');
                if (data.role == 'nonasn') $('#title-form-section-sso_asn_nonasn').text('Form Non ASN');

                $('#create_sso-auth_provider_user_id').val(data.id)
                $('#create_sso-name').val(data.profile.nama)
                $('#create_sso-email').val(data.email)
                $('#create_sso-username').val(data.username)

                if (data.opd.length >= 1) {
                    $('#create_sso-opd_ids').val(data.opd.map(opd => opd.id)).trigger('change')
                } else {
                    $('#create_sso-opd_ids').val('').trigger('change')
                }

                $('#create_sso-nik').val(data.profile.nik)
                $('#create_sso-no_hp').val(data.profile.no_hp)
                $('#create_sso-nip').val(data.profile.nip)
                $('#create_sso-alamat').val(data.profile.alamat)
                $('#create_sso-tempat_lahir').val(data.profile.tempat_lahir)
                $('#create_sso-tanggal_lahir').val(data.profile.tanggal_lahir)
                $('#create_sso-jenis_kelamin').val(data.profile.jenis_kelamin)

                if (data.profile.foto_profil_url != null) {
                    $('#create_sso-photo_profile_url').val(data.profile.foto_profil_url)
                    $('#create_sso-photo_profile').attr('disabled', true).addClass('disabled')
                    $('#create_sso-photo_profile-preview').attr('src', data.profile.foto_profil_url)
                } else {
                    $('#create_sso-photo_profile_url').val('')
                    $('#create_sso-photo_profile').attr('disabled', false).removeClass('disabled')
                    $('#create_sso-photo_profile-preview').attr('src',
                        `{{ asset('assets/global/img/placeholder/img-placeholder-square.png') }}`)
                }
            }

            if (data.role == 'admin' || data.role == 'opd') {
                showSwal('Admin SSO maupun Admin OPD tidak diperbolehkan menambahkan data user ke aplikasi ini!',
                    'error')
            }
        });

        $('#form-update_lock_status').submit(function(e) {
            e.preventDefault()

            formMode = 'update_lock_status'

            const formData = new FormData(this)
            formData.append('_token', getCsrfToken())
            formData.append('_method', 'patch')

            callApi(formData, `{{ url()->current() }}/lock-status/${$('#update_lock_status-id').val()}`)
        })

        $('#table tbody').on('click', '.btn-update-lock-status', function() {
            const detail = $(this).data('detail')

            $('#modal-update_lock_status').modal('show')

            for (const key in detail) {
                if (detail.hasOwnProperty(key)) {
                    $(`#update_lock_status-${key}`).val(detail[key])
                }
            }

            $(`#update_lock_status-id`).val(detail.id)
        })

        $('#table tbody').on('click', '.btn-delete', function() {
            const id = $(this).data('id')

            showSwalConfirm('Hapus', 'Ingin mengahpus data?', 'warning', function(
                result) {
                if (result) {
                    const formData = new FormData()

                    formData.append('_method', 'delete')
                    formData.append('_token', getCsrfToken())
                    formData.append('id', id)

                    callApi(formData, `{{ url()->current() }}/${id}`)
                }
            })
        })

        function callApi(formData, url = null) {
            const submitLabel = $(`#form-${formMode} button[type="submit"]`).text()

            startSpinSubmitBtn(`#form-${formMode}`)

            $.ajax({
                url: url || "{{ url()->current() }}",
                method: "POST",
                data: formData,
                dataType: "JSON",
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    clearValidationMessage(formMode, 'msg')
                },
                success: function(response) {
                    $(`#form-${formMode}`).trigger('reset')
                    $(`#modal-${formMode}`).modal('hide')
                    showSwal(response.message, response.status)

                    table.ajax.reload()
                },
                error: function(response) {
                    handleAjaxJqueryError(response, {
                        formPrefix: formMode,
                        msgSuffix: 'msg'
                    })
                },
                complete: function() {
                    stopSpinSubmitBtn(`#form-${formMode}`, submitLabel)
                }
            });
        }
    </script>
@endpush
