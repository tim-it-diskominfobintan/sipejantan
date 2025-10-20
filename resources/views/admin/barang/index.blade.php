@extends('layouts.admin.main')

@section('action-header')
    {{-- UNTUK TOMBOL TAMBAH DATA DLL, KALO GAADA KOSONGIN AJA --}}
    <div class="col-auto ms-auto d-print-none">
        {{-- <div class="btn-list">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-create">
                <i class="bi bi-plus-lg me-2"></i> Tambah {{ $title }}
            </button>
        </div> --}}
        <div class="col-auto ms-auto d-print-none">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-create">
                <i class="bi bi-plus-lg me-2"></i> Tambah {{ $title }}
            </button>
            <a href="{{ url('admin/asset/create') }}" class="btn btn-success" hidden>
                <i class="bi bi-printer me-2"></i> Cetak {{ $title }}
            </a>
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
                                    <th>Nama Barang</th>
                                    <th>Satuan</th>
                                    <th>Stok Sekarang</th>
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

    @include('admin.barang.modal')
@endsection

@push('script')
    <script>
        let formMode = 'create'

        const table = $('#table').DataTable({
            serverSide: true,
            columnDefs: [{
                    targets: [-1],
                    className: "text-center",
                    responsivePriority: 1,
                    width: '15%'
                },
                {
                    targets: [0],
                    className: "text-center",
                    responsivePriority: 1,
                    width: '8%'
                },
                {
                    targets: [1],
                    className: "text-left",
                    responsivePriority: 1,
                    width: '20%'
                }
            ],
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
                    data: 'nama_barang',
                    name: 'nama_barang'
                },
                {
                    data: 'satuan',
                    name: 'satuan',
                },
                {
                    data: 'stok_sekarang',
                    name: 'stok_sekarang',
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

        $('#table tbody').on('click', '.btn-opname', function() {
            let detail = $(this).data('detail')
            // $('#opname-barang').val(detail.kode_barang + ' - ' + detail.nama_barang);
            let barangId = $(this).data('id');
            $.get(`{{ url('admin/barang/list_detail_barang') }}/${barangId}`, function(res) {
                let options = `<option value="">Pilih Detail Barang</option>`;
                if (res.length > 0) {
                    res.forEach(item => {
                        options += `<option value="${item.id_detail_barang}">
                                ${item.kode_barang} 
                            </option>`;
                    });
                } else {
                    options = `<option value="" disabled>Tidak ada detail barang</option>`;
                }
                $('#opname-barang').html(options);
                $('#opname-barang_id').val(detail.id_barang);
                $('#modal-opname').modal('show')
            });
        });

        $('#form-opname').submit(function(e) {
            e.preventDefault()

            formMode = 'opname'

            const formData = new FormData(this)
            formData.append('_token', getCsrfToken())
            formData.append('_method', 'post')

            callApi(formData, `{{ url('admin/stok_opname') }}`);
        })

        $('#table tbody').on('click', '.btn-list-opname', function() {
            let detail = $(this).data('detail')
            let barangId = $(this).data('id');
            $('#table-opname tbody').html('<tr><td colspan="5" class="text-center">Loading...</td></tr>');

            $.ajax({
                url: `{{ url('admin/stok_opname/list') }}/${barangId}`,
                type: "GET",
                success: function(res) {
                    let rows = "";
                    if (res.length > 0) {
                        res.forEach(item => {
                            console.log(item);
                            rows += `
                        <tr>
                            <td>${item.detailbarang.kode_barang}</td>
                            <td>${tanggalIndo(item.tanggal_opname)}</td>
                            <td>
                                ${item.jenis_opname === 'masuk' 
                                    ? '<span class="badge bg-green-lt">Masuk</span>' 
                                    : item.jenis_opname === 'keluar' 
                                        ? '<span class="badge bg-yellow-lt">Keluar</span>' 
                                        : '<span class="badge bg-red-lt">Rusak</span>'}
                            </td>
                            <td class="text-center">${item.jumlah_opname} ${item.detailbarang.barang.satuan ?? '-'}</td>
                            <td>${item.no_bukti ?? '-'}</td>
                            <td>${item.keterangan ?? '-'}</td>
                            <td>${item.created_by ?? '-'}</td>
                        </tr>
                    `;
                        });
                    } else {
                        rows =
                            `<tr><td colspan="7" class="text-center">Tidak ada data opname</td></tr>`;
                    }
                    $('#table-opname tbody').html(rows);
                },
                error: function() {
                    $('#table-opname tbody').html(
                        `<tr><td colspan="7" class="text-center text-danger">Gagal memuat data</td></tr>`
                    );
                }
            });
            $('#modal-list-opname').modal('show')
        });

        $('#table tbody').on('click', '.btn-update', function() {
            let detail = $(this).data('detail')

            $('#modal-update').modal('show')
            $(`#update-id_barang`).val(detail.id_barang)
            $(`#update-nama_barang`).val(detail.nama_barang)
            $(`#update-satuan`).val(detail.satuan).trigger('change')
        })

        $('#form-update').submit(function(e) {
            e.preventDefault()

            formMode = 'update'

            const formData = new FormData(this)
            formData.append('_token', getCsrfToken())
            formData.append('_method', 'put')

            callApi(formData, `{{ url()->current() }}/${$('#update-id_barang').val()}`)
        })

        $('#table tbody').on('click', '.btn-delete', function() {
            const id = $(this).data('id')

            showSwalConfirm('Hapus', 'Semua data yang terkait akan dihapus?', 'warning', function(
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

                    window.location.reload()
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

        $('#opname-jenis_opname').on('change', function() {
            const container = $('#container-no_bukti');

            if (this.value === 'keluar' || this.value === 'rusak') {
                $.get('/trans_barang_perbaikan', function(data) {
                    let options = `<option value="" disabled selected>Pilih Transaksi Perbaikan</option>`;
                    data.forEach(item => {
                        options +=
                            `<option value="${item.id_perbaikan}">ID : ${item.id_perbaikan} - No Laporan : ${item.laporan.no_laporan ?? '-'}</option>`;
                    });

                    container.html(`
                <label for="opname-no_bukti" class="form-label">No Bukti / ID Perbaikan</label>
                <select id="opname-no_bukti" name="no_bukti" class="form-select">
                    ${options}
                </select>
                <div id="opname-no_bukti-msg"></div>
            `);
                });
            } else {
                container.html(`
                    <label for="opname-no_bukti" class="form-label">No Bukti / ID Perbaikan</label>
                    <input type="text" id="opname-no_bukti" name="no_bukti" class="form-control" placeholder="No Bukti">
                    <div id="opname-no_bukti-msg"></div>
                `);
            }
        });
    </script>
@endpush
