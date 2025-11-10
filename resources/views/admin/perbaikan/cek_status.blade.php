@extends('layouts.admin.main')
@section('action-header')
    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cekModal">
                <i class="bi bi-check-lg me-2"></i> Konfirmasi Status
            </button>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">
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

        /* Responsive fix */
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
                                <img src="{{ asset('assets/global/img/laporan.png') }}" alt="Foto Laporan"
                                    class="rounded shadow-sm border"
                                    style="width:100px; height:100px; object-fit:cover; cursor:pointer;"
                                    data-bs-toggle="modal" data-bs-target="#modalLaporan">
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
        </div>
    </div>

    @include('admin.perbaikan.modal')
@endsection

@push('script')
    <script>
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

        $('#form-update').submit(function(e) {
            e.preventDefault()

            formMode = 'update'

            const formData = new FormData(this)
            formData.append('_token', getCsrfToken())
            formData.append('_method', 'post')

            callApi(formData)
        })

        function callApi(formData, url = null) {
            const submitLabel = $(`#form-${formMode} button[type="submit"]`).text()
            let id = $('#id').val();
            startSpinSubmitBtn(`#form-${formMode}`)

            $.ajax({
                url: "{{ url('admin/cek_status') }}/" + id,
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

                    window.location = "{{ url('admin/laporan') }}";
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
