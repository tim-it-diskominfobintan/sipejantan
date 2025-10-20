@extends('layouts.admin.main')

@section('action-header')
    {{-- UNTUK TOMBOL TAMBAH DATA DLL, KALO GAADA KOSONGIN AJA --}}
    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list gap-3">
            <button type="button" class="btn btn-4" id="btn-get_opd_bintan_sso">
                <i class="bi bi-plus-lg me-2"></i> Ambil data {{ $title }} dari Bintan SSO
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
                    {{-- <button id="reload" class="ms-5">Reload</button> --}}
                </div>
                <div class="card-body py-0 px-0 mx-0">
                    <div class="table-responsive table-full-to-card-body py-3">
                        <table class="table" id="table">
                            <thead>
                                <tr class="bg-body-tertiary">
                                    <th width="20px">No</th>
                                    <th>Nama</th>
                                    <th>Alias</th>
                                    <th>logo</th>
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

    @include('admin.master.opd.modal')
@endsection

@push('script')
    <script>
        let formMode = 'create'
        
        if (`{{ session()->get('bintan-sso.access_token') }}` != '') {
            fetchOpdFromSso()
        }

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
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'alias',
                    name: 'alias'
                },
                {
                    data: 'logo',
                    name: 'logo',
                    orderable: false,
                    searchable: false
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

        $('#create-logo').change(function() {
            previewImageFromInput(this, '#create-logo-preview');
        });

        $('#update-logo').change(function() {
            previewImageFromInput(this, '#update-logo-preview');
        });

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

            callApi(formData, `{{ url()->current() }}/${$('#update-id').val()}`)
        })

        $('#table tbody').on('click', '.btn-update', function() {
            let detail = $(this).data('detail')

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

            $(`#update-id`).val(detail.id)
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

        $('#btn-authenticate-sso').click(function() {
            window.location.href = "{{ url('auth/bintan-sso/login') }}"
        })

        // $('#sso-opd').select2({
        //     language: 'id',
        //     dropdownParent: '#modal-sso',
        //     placeholder: 'Cari OPD...',
        //     theme: 'bootstrap-5',
        //     width: '100%',
        //     allowClear: true,
        //     minimumInputLength: 2,
        //     ajax: {
        //         url: "{{ env('BINTAN_SSO_ENDPOINT') . '/api/opd' }}",
        //         dataType: 'json',
        //         headers: {
        //             Authorization: `Bearer {{ session()->get('bintan-sso.access_token') }}`
        //         },
        //         method: 'GET',
        //         delay: 250,
        //         data: function(params) {
        //             return {
        //                 keyword: params.term,
        //             };
        //         },
        //         error: function(response) {
        //             handleAjaxJqueryError(response, {
        //                 onUnauthorized: function() {}
        //             })
        //         },
        //         processResults: function(response) {
        //             if (response.error) {
        //                 return {
        //                     results: []
        //                 };
        //             }

        //             const results = response.data.map(function(data) {
        //                 return {
        //                     id: JSON.stringify(data),
        //                     text: data.nama
        //                 };
        //             });
                    
        //             return {
        //                 results: results
        //             };
        //         },
        //         cache: true // Mengaktifkan cache agar tidak mengirimkan permintaan yang sama berulang-ulang
        //     }
        // });

        $('#btn-get_opd_bintan_sso').click(function() {
            fetchOpdFromSso()
            $('#modal-sso').modal('show')

            const ssoToken = '{{ session()->get('bintan-sso.access_token') }}'

            if (ssoToken == '') {
                $('#modal-sso-footer-action').hide()
                $('#btn-authenticate-sso').show()
            } else {
                $('#modal-sso-footer-action').show()
                $('#btn-authenticate-sso').hide()
            }

            $('#sso-username').val('').trigger('change')
            $('#sso-username').trigger('click')
        })

        $('#form-sso').submit(function(e) {
            e.preventDefault()

            const formData = new FormData(this)
            formData.append('_token', getCsrfToken())
            formData.append('_method', 'post')

            callApi(formData, "{{ url('admin/master/opd/sso') }}")

            $('#modal-sso').modal('hide')
        })

        function fetchOpdFromSso() {
            $.ajax({
                 url: "{{ env('BINTAN_SSO_ENDPOINT') . '/api/opd' }}",
                dataType: 'json',
                headers: {
                    Authorization: `Bearer {{ session()->get('bintan-sso.access_token') }}`
                },
                method: 'GET',
                error: function(response) {
                    handleAjaxJqueryError(response, {
                        onUnauthorized: function() {
                            showSwal('Anda tidak terautentikasi pada Bintan SSO. Login SSO terlebih dahulu untuk dapat mengambil data OPD', 'warning')
                        }
                    })
                },
                success: function(response) {
                    if (response.error) {
                        return;
                    }

                    const results = response.data.map(function(data) {
                        return new Option(data.nama, JSON.stringify(data), true, false)
                    });

                    $('#sso-opd').empty()
                    $('#sso-opd').append(results).trigger('change');
                    $('#sso-opd').select2({
                        language: 'id',
                        dropdownParent: '#modal-sso',
                        placeholder: 'Cari OPD...',
                        theme: 'bootstrap-5',
                        width: '100%',
                        allowClear: true,
                        minimumInputLength: 2
                    })
                }       
            })
        }

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
