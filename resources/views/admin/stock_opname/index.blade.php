@extends('layouts.admin.main')
@section('action-header')
    {{-- UNTUK TOMBOL TAMBAH DATA DLL, KALO GAADA KOSONGIN AJA --}}
    <div class="col-auto ms-auto d-print-none" hidden>
        <div class="btn-list">
            <a href="{{ url('admin/stok_opname/create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i> Tambah {{ $title }}
            </a>
        </div>
    </div>
@endsection

@section('content')
    <style>
        .btn-primary {
            background: linear-gradient(120deg, #4e73df 0%, #5978d5 100%);
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s;
        }
    </style>
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
                                    <th>Detail Barang</th>
                                    <th>Tanggal</th>
                                    <th>IN / OUT</th>
                                    <th>Jumlah</th>
                                    <th>No Bukti / ID Perbaikan</th>
                                    <th>Keterangan</th>
                                    <th>Oleh</th>
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
    @include('admin.stock_opname.modal')
@endsection

@push('script')
    <script>
        let formMode = 'create'

        const table = $('#table').DataTable({
            serverSide: true,
            columnDefs: [{
                targets: [0],
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
                    data: 'detail_barang',
                    name: 'detail_barang'
                },
                {
                    data: 'tanggal',
                    name: 'tanggal'
                },
                {
                    data: 'jenis',
                    name: 'jenis'
                },
                {
                    data: 'jumlah_opname',
                    name: 'jumlah_opname'
                },
                {
                    data: 'no_bukti',
                    name: 'no_bukti'
                },
                {
                    data: 'keterangan',
                    name: 'keterangan'
                },
                {
                    data: 'created_by',
                    name: 'created_by'
                },
                {
                    data: 'actions',
                    name: 'actions'
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

            callApi(formData, `{{ url()->current() }}/${$('#update-id_opname').val()}`)
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

            $(`#update-id_opname`).val(detail.id_opname)
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

        $('#create-jenis_opname').on('change', function() {
            const container = $('#container-no_bukti');

            if (this.value === 'keluar') {
                // request ke route Laravel misalnya: /get-perbaikan
                $.get('/trans_barang_perbaikan', function(data) {
                    console.log(data)
                    let options = `<option value="" disabled selected>Pilih Transaksi Perbaikan</option>`;
                    data.forEach(item => {
                        options +=
                            `<option value="${item.id_perbaikan}">ID : ${item.id_perbaikan} - No Laporan : ${item.laporan.no_laporan ?? '-'}</option>`;
                    });

                    container.html(`
                <label for="create-no_bukti" class="form-label">No Bukti / ID Perbaikan</label>
                <select id="create-no_bukti" name="no_bukti" class="form-select">
                    ${options}
                </select>
                <div id="create-no_bukti-msg"></div>
            `);
                });
            } else {
                container.html(`
            <label for="create-no_bukti" class="form-label">No Bukti / ID Perbaikan</label>
            <input type="text" id="create-no_bukti" name="no_bukti" class="form-control" placeholder="No Bukti">
            <div id="create-no_bukti-msg"></div>
        `);
            }
        });
    </script>
@endpush
