@extends('layouts.admin.main')
@section('action-header')
    {{-- UNTUK TOMBOL TAMBAH DATA DLL, KALO GAADA KOSONGIN AJA --}}
    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list justify-content-between">
            <a href="{{ url('admin/asset/create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i> Tambah {{ $title }}
            </a>
            <a href="{{ url('admin/asset/export') }}" class="btn btn-success" target="_blank">
                <i class="bi bi-file-earmark-excel me-2"></i> Export
            </a>
            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalImport">
                <i class="bi bi-upload me-2"></i> Import
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
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <div class="card-body py-0 px-0 mx-0">
                    <div class="table-responsive table-full-to-card-body">
                        <table class="table" id="table">
                            <thead>
                                <tr class="bg-body-tertiary">
                                    <th width="20px">No</th>
                                    <th>Kode Asset</th>
                                    <th>Jenis Asset</th>
                                    <th>Jalan</th>
                                    <th>Penanggung Jawab</th>
                                    <th>Kecamatan</th>
                                    <th>Kelurahan</th>
                                    <th>Kondisi</th>
                                    {{-- <th>Foto</th> --}}
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
    @include('admin.asset.modal')
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>
    <script>
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
                    data: 'kode',
                    name: 'kode'
                },
                {
                    data: 'jenis_asset',
                    name: 'jenis_asset'
                },
                {
                    data: 'nama_jalan',
                    name: 'nama_jalan'
                },
                {
                    data: 'nama_penanggung_jawab',
                    name: 'nama_penanggung_jawab'
                },
                {
                    data: 'nama_kecamatan',
                    name: 'nama_kecamatan'
                },
                {
                    data: 'nama_kelurahan',
                    name: 'nama_kelurahan'
                },
                {
                    data: 'kondisi',
                    name: 'kondisi'
                },
                // {
                //     data: 'foto',
                //     name: 'foto'
                // },
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

        const barcodeModal = document.getElementById('barcodeModal');
        barcodeModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const kodeBarang = button.getAttribute('data-kode');

            // Set kode barang di modal
            document.getElementById('barcode-kode-text').textContent = kodeBarang;

            // Hapus QRCode lama biar gak numpuk
            document.getElementById("qrcode").innerHTML = "";

            // Generate QRCode baru
            new QRCode(document.getElementById("qrcode"), {
                text: kodeBarang,
                width: 150,
                height: 150,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        });

        // Fungsi cetak barcode
        document.getElementById('print-barcode').addEventListener('click', function() {
            const printContent = `
                    <div style="text-align: center; padding: 20px;" >
                        <h3>Barcode Barang</h3>
                        <div style="display: flex; justify-content: center;">
                            ${document.getElementById('qrcode').innerHTML}
                        </div>
                        <p style="margin-top: 15px; font-weight: bold;">${document.getElementById('barcode-kode-text').textContent}</p>
                        <p>${new Date().toLocaleDateString('id-ID')}</p>
                    </div>
                `;

            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                    <html>
                        <head>
                            <title>Cetak Barcode</title>
                            <style>
                                body { font-family: Arial, sans-serif; }
                                @media print {
                                    @page { margin: 0; }
                                    body { margin: 1.6cm; }
                                }
                            </style>
                        </head>
                        <body onload="window.print(); window.close();">
                            ${printContent}
                        </body>
                    </html>
                `);
            printWindow.document.close();
        });
    </script>
@endpush
