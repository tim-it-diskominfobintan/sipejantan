@extends('layouts.admin.main')

@section('action-header')
    {{-- UNTUK TOMBOL TAMBAH DATA DLL, KALO GAADA KOSONGIN AJA --}}
    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
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
                    {{-- <button id="reload" class="ms-5">Reload</button> --}}
                </div>
                <div class="card-body py-0 px-0 mx-0">
                    <div class="table-responsive table-full-to-card-body">
                        <table class="table" id="table">
                            <thead>
                                <tr class="bg-body-tertiary">
                                    <th width="20px">No</th>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>No HP</th>
                                    <th>Kelurahan</th>
                                    <th>Kecamatan</th>
                                    <th>Foto</th>
                                    <th>Nama Penanggung Jawab</th>
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

    @include('admin.master.teknisi.modal')
@endsection

@push('script')
    <script>
        // initializeSelect2('.select2', '#form-create')
        $('#create-kecamatan_id').select2({
            placeholder: "Pilih Kecamatan",
            width: '100%',
            dropdownParent: $('#modal-create')
        });

        $('#create-kelurahan_id').select2({
            placeholder: "Pilih Kelurahan",
            width: '100%',
            dropdownParent: $('#modal-create')
        });

        $('#create-kecamatan_id').on('change', function() {
            let kecamatanId = $(this).val();

            // reset kelurahan
            $('#create-kelurahan_id').empty().append('<option value="">Pilih Kelurahan</option>').prop('disabled',
                true);

            if (kecamatanId) {
                $.ajax({
                    url: `/pengaduan/${kecamatanId}`,
                    type: "GET",
                    success: function(data) {
                        if (data.length > 0) {
                            data.forEach(function(kel) {
                                $('#create-kelurahan_id').append(
                                    `<option value="${kel.id_kelurahan}">${kel.nama_kelurahan}</option>`
                                );
                            });

                            // enable dropdown kelurahan
                            $('#create-kelurahan_id').prop('disabled', false);
                        }
                    }
                });
            }
        });

        $('#update-kecamatan_id').select2({
            placeholder: "Pilih Kecamatan",
            width: '100%',
            dropdownParent: $('#modal-update')
        });

        $('#update-kelurahan_id').select2({
            placeholder: "Pilih Kelurahan",
            width: '100%',
            dropdownParent: $('#modal-update')
        });

        // saat ganti kecamatan
        $('#update-kecamatan_id').on('change', function() {
            loadKelurahan($(this).val(), null); // null karena bukan mode edit
        });

        // fungsi reusable untuk load kelurahan
        function loadKelurahan(kecamatanId, selectedKelurahanId = null) {
            $('#update-kelurahan_id')
                .empty()
                .append('<option value="">Pilih Kelurahan</option>')
                .prop('disabled', true);

            if (kecamatanId) {
                $.ajax({
                    url: `/pengaduan/${kecamatanId}`,
                    type: "GET",
                    success: function(data) {
                        if (data.length > 0) {
                            data.forEach(function(kel) {
                                let selected = (selectedKelurahanId == kel.id_kelurahan) ? 'selected' :
                                    '';
                                $('#update-kelurahan_id').append(
                                    `<option value="${kel.id_kelurahan}" ${selected}>${kel.nama_kelurahan}</option>`
                                );
                            });

                            $('#update-kelurahan_id').prop('disabled', false).trigger('change');
                        }
                    }
                });
            }
        }

        let formMode = 'create'

        const table = $('#table').DataTable({
            serverSide: true,
            columnDefs: [{
                targets: [0, -1],
                className: "text-center",
                responsivePriority: 1,
                width: '10%'
            }],
            ajax: {
                url: "{{ url()->current() }}",
                method: 'GET',
                beforeSend: function() {
                    renderRowSpinner();
                },
                error: function(response) {
                    handleAjaxJqueryError(response)
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
                    data: 'nik',
                    name: 'nik'
                },
                {
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'telp',
                    name: 'telp'
                },
                {
                    data: 'nama_kelurahan',
                    name: 'nama_kelurahan'
                },
                {
                    data: 'nama_kecamatan',
                    name: 'nama_kecamatan'
                },
                {
                    data: 'ft_teknisi',
                    name: 'ft_teknisi'
                },
                {
                    data: 'nama_penanggung_jawab',
                    name: 'nama_penanggung_jawab'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $('#reload').click(function() {
            table.ajax.reload()
        })

        $('#form-create').submit(function(e) {
            e.preventDefault()

            formMode = 'create'

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

            callApi(formData, `{{ url()->current() }}/${$('#update-id_teknisi').val()}`)
        })

        $('#table tbody').on('click', '.btn-update', function() {
            let detail = $(this).data('detail')
            // console.log(detail.user.username)
            $('#modal-update').modal('show')

            for (const key in detail) {
                if (detail.hasOwnProperty(key)) {
                    $(`#update-${key}`).val(detail[key])

                    if (key.includes('_url')) {
                        let newKey = key.replace('_url', '')
                        console.log(newKey)
                        $(`#update-${newKey}-preview`).attr('src', detail[key])
                    }
                }
            }

            $(`#update-id_teknisi`).val(detail.id_teknisi)
            $(`#update-kelurahan_id`).val(detail.kelurahan_id).trigger('change')
            $(`#update-kecamatan_id`).val(detail.kecamatan_id).trigger('change')
            $(`#update-alamat_teknisi`).text(detail.alamat_teknisi)
            $(`#update-username`).val(detail.user.username)

            let kecamatanId = $('#update-kecamatan_id').val(); // sudah otomatis selected dari Blade
            let kelurahanId = detail.kelurahan_id; // data lama yang harus selected
            loadKelurahan(kecamatanId, kelurahanId);
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
