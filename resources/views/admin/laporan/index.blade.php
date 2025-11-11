@extends('layouts.admin.main')

@section('action-header')
    {{-- UNTUK TOMBOL TAMBAH DATA DLL, KALO GAADA KOSONGIN AJA --}}
    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            <a href="{{ route('laporan.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i> Tambah {{ $title }}
            </a>
            <a href="{{ route('laporan.export') }}" class="btn btn-success" target="_blank">
                <i class="bi bi-file-earmark-excel me-2"></i> Export
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
                    {{-- buatkan filter per kecamatan, kelurahan dan status laporan --}}
                    <div class="row g-3 py-3 px-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                <select id="filter-kecamatan" class="form-select">
                                    <option value="" selected>Semua Kecamatan</option>
                                    @foreach ($kecamatan as $item)
                                        <option value="{{ $item->id_kecamatan }}">{{ $item->nama_kecamatan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                <select id="filter-kelurahan" class="form-select">
                                    <option value="" selected>Semua Kelurahan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-bell"></i></span>
                                <select id="filter-status" class="form-select">
                                    <option value="" selected>Semua Status Laporan</option>
                                    <option value="pending">Pending</option>
                                    <option value="proses">Proses</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="ditolak">Ditolak</option>
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive table-full-to-card-body">
                            <table class="table" id="table">
                                <thead>
                                    <tr class="bg-body-tertiary">
                                        <th width="20px">No</th>
                                        <th>Tanggal Laporan</th>
                                        <th>Pelapor</th>
                                        <th>No Laporan</th>
                                        <th>Nama Asset</th>
                                        <th>Penanggung Jawab</th>
                                        <th>No HP</th>
                                        <th>Status Laporan</th>
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
    </div>
    @include('admin.laporan.modal')
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
                    className: "text-center",
                    responsivePriority: 1,
                    width: '15%'
                },
            ],
            ajax: {
                url: "{{ url()->current() }}",
                method: 'GET',
                data: function(d) {
                    d.kecamatan = $('#filter-kecamatan').val()
                    d.kelurahan = $('#filter-kelurahan').val()
                    d.status = $('#filter-status').val()
                },
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
                    data: 'tgl_lapor',
                    name: 'tgl_lapor'
                },
                {
                    data: 'pelapor',
                    name: 'pelapor',
                    className: 'text-center'
                },
                {
                    data: 'no_lapor',
                    name: 'no_lapor',
                    className: 'text-center'
                },
                {
                    data: 'nama_asset',
                    name: 'nama_asset',
                    className: 'text-center'
                },
                {
                    data: 'pj',
                    name: 'pj',
                    className: 'text-center'
                },
                {
                    data: 'telp',
                    name: 'telp',
                    className: 'text-center'
                },
                {
                    data: 'status',
                    name: 'status',
                    className: 'text-center'
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

        $('#filter-kecamatan').on('change', function() {
            let kecamatanId = $(this).val();

            // kosongkan kelurahan
            $('#filter-kelurahan').empty().append('<option value="">Filter Kelurahan</option>');

            if (kecamatanId) {
                $.ajax({
                    url: '/kelurahan/' + kecamatanId,
                    type: 'GET',
                    success: function(data) {
                        $.each(data, function(i, kel) {
                            $('#filter-kelurahan').append(
                                `<option value="${kel.id_kelurahan}">${kel.nama_kelurahan}</option>`
                            );
                        });
                    }
                });
            }

            // reload datatable biar langsung filter kecamatan
            table.ajax.reload();
        });

        // kalau kelurahan berubah, reload juga
        $('#filter-kelurahan').on('change', function() {
            table.ajax.reload();
        });

        // filter status
        $('#filter-status').on('change', function() {
            table.ajax.reload();
        });

        $('#table tbody').on('click', '.btn-teknisi', function() {
            let detail = $(this).data('detail')
            $('#update-status_laporan').val(detail.laporan.status_laporan).trigger('change');
            $('#update-teknisi_id').val(detail.laporan.teknisi_id).trigger('change').prop('disabled', true);
            $('#update-id_laporan').val(detail.laporan.id_laporan);
            $('#teknisiModal').modal('show')
        })

        $('#form-update').submit(function(e) {
            e.preventDefault()

            formMode = 'update'

            const formData = new FormData(this)
            formData.append('_token', getCsrfToken())
            formData.append('_method', 'put')

            callApi(formData, `{{ url()->current() }}`)
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

        $('#update-status_laporan').change(function() {
            const status = $(this).val();
            if (status === 'ditolak') {
                $('#keterangan-tolak').show();
                $('#update-teknisi_id').prop('disabled', true);
            } else if (status === 'proses') {
                $('#update-teknisi_id').prop('disabled', false);
                $('#keterangan-tolak').hide();
                $('#update-ket_tolak').val('');
            } else {
                $('#keterangan-tolak').hide();
                $('#update-ket_tolak').val(''); // Clear the input field if not rejected
            }
        });

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
    </script>
@endpush
