@extends('layouts.admin.main')
@section('action-header')
    {{-- UNTUK TOMBOL TAMBAH DATA DLL, KALO GAADA KOSONGIN AJA --}}
    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            <a href="{{ url('admin/perbaikan/create/' . $pelapor->id_pelapor) }}" class="btn btn-primary"
                {{ $laporan->status_laporan == 'selesai' ? 'hidden' : '' }}>
                <i class="bi bi-plus-lg me-2"></i> Tambah {{ $title }}
            </a>
            <a href="{{ url('admin/laporan') }}" class="btn btn-secondary">
                <i class="bi bi-skip-backward-fill me-2"></i>
                Kembali
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

        @media (max-width: 767.98px) {
            .info-flex {
                flex-direction: column !important;
                text-align: center !important;
            }

            .info-flex img {
                margin: 0 auto 15px auto !important;
                display: block;
            }

            .info-detail {
                text-align: left !important;
            }
        }
    </style>
    <div class="page-body">
        <div class="container-xl">
            <div class="row mb-4 g-3">
                <!-- Data Pelapor -->
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100 rounded-3">
                        <div class="card-header bg-primary text-white fw-bold rounded-top-3">
                            <i class="bi bi-person-circle me-1"></i> Data Pelapor
                        </div>
                        <div class="card-body d-flex align-items-start info-flex">
                            <!-- Foto -->
                            <div class="me-3 flex-shrink-0 text-center">
                                <img src="{{ asset('storage/' . $pelapor->foto_identitas) }}" alt="Foto Identitas"
                                    class="rounded shadow-sm border"
                                    style="width:130px; height:100px; object-fit:cover; cursor:pointer;"
                                    data-bs-toggle="modal" data-bs-target="#modalPelapor">
                            </div>

                            <!-- Detail -->
                            <div class="w-100 info-detail">
                                <div class="row mb-2">
                                    <div class="col-4 fw-bold text-muted">NIK</div>
                                    <div class="col-8">{{ $pelapor->nik }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4 fw-bold text-muted">Nama</div>
                                    <div class="col-8">{{ $pelapor->nama }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4 fw-bold text-muted">Email</div>
                                    <div class="col-8">{{ $pelapor->email }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4 fw-bold text-muted">No Telp</div>
                                    <div class="col-8">{{ $pelapor->no_hp }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-4 fw-bold text-muted">Alamat</div>
                                    <div class="col-8">
                                        {{ $pelapor->alamat }},
                                        Kel. {{ $pelapor->kelurahan->nama_kelurahan }},
                                        Kec. {{ $pelapor->kecamatan->nama_kecamatan }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Laporan -->
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100 rounded-3">
                        <div class="card-header bg-success text-white fw-bold rounded-top-3">
                            <i class="bi bi-file-earmark-text me-1"></i> Data Laporan
                        </div>
                        <div class="card-body d-flex align-items-start info-flex">
                            <!-- Foto Laporan -->
                            <div class="me-3 flex-shrink-0 text-center">
                                <img src="{{ asset('storage/' . $pelapor->foto_identitas) }}" alt="Foto Identitas"
                                    class="rounded shadow-sm border"
                                    style="width:130px; height:100px; object-fit:cover; cursor:pointer;"
                                    data-bs-toggle="modal" data-bs-target="#modalPelapor">
                            </div>

                            <!-- Detail -->
                            <div class="w-100 info-detail">
                                <div class="row mb-2">
                                    <div class="col-4 fw-bold text-muted">No Laporan</div>
                                    <div class="col-8">
                                        <span class="badge bg-blue-lt">{{ $laporan->no_laporan }}</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4 fw-bold text-muted">Status</div>
                                    <div class="col-8">
                                        <span
                                            class="badge 
                                            @if ($laporan->status_laporan == 'pending') bg-red text-red-fg
                                            @elseif($laporan->status_laporan == 'proses') bg-blue text-blue-fg 
                                            @elseif($laporan->status_laporan == 'diterima') bg-orange text-orange-fg 
                                            @else bg-green text-green-fg @endif">
                                            {{ ucfirst($laporan->status_laporan) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4 fw-bold text-muted">Tanggal</div>
                                    <div class="col-8">
                                        {{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->format('d F Y') }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4 fw-bold text-muted">Deskripsi</div>
                                    <div class="col-8 text-secondary">
                                        {{ $laporan->deskripsi_laporan }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                                    <th>No Laporan</th>
                                    <th>Jenis Laporan</th>
                                    <th>Status</th>
                                    <th>Tanggal Perbaikan</th>
                                    <th>Keterangan</th>
                                    <th>File</th>
                                    <th>Teknisi</th>
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
    @include('admin.perbaikan.modal')
@endsection

@push('script')
    <script>
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
                    data: 'no_laporan',
                    name: 'no_laporan'
                },
                {
                    data: 'jenis_laporan',
                    name: 'jenis_laporan'
                },
                {
                    data: 'progress',
                    name: 'progress'
                },
                {
                    data: 'tgl',
                    name: 'tgl'
                },
                {
                    data: 'keterangan',
                    name: 'keterangan'
                },
                {
                    data: 'file',
                    name: 'file'
                },
                {
                    data: 'teknisi',
                    name: 'teknisi'
                },
                {
                    data: 'actions',
                    name: 'actions'
                },
            ]
        });

        $(document).on('click', '.btn-dok', function() {
            let dok = $(this).data('dok'); // array file dari controller
            let container = $('#dok-preview');
            container.html('');

            if (dok.length > 0) {
                dok.forEach(function(item) {
                    let img = `<div class="p-2 border rounded">
                            <img src="/storage/${item.file_perbaikan}" class="img-fluid" style="max-width:150px; max-height:150px; object-fit:cover;">
                            <div class="small text-muted" hidden>${item.tanggal_dokumen}</div>
                        </div>`;
                    container.append(img);
                });
            } else {
                container.html('<p class="text-muted">Tidak ada dokumentasi.</p>');
            }

            $('#dokModal').modal('show');
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
                    formMode = 'delete'
                    formData.append('_method', 'delete')
                    formData.append('_token', getCsrfToken())
                    formData.append('id_perbaikan', id)

                    callApi(formData, `{{ url('admin/perbaikan/delete') }}/${id}`)
                }
            })
        })

        function callApi(formData, url = null) {
            const submitLabel = $(`#form-${formMode} button[type="submit"]`).text()

            startSpinSubmitBtn(`#form-${formMode}`)

            $.ajax({
                url: url,
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
