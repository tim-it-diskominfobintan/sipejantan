@extends('layouts.admin.main')

@section('content')
    <style>
        /* === FIX LAYOUT RESPONSIVE === */
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

            .card .card-header {
                text-align: center;
                font-size: 14px;
            }

            table.table {
                font-size: 12px;
            }

            table.table th,
            table.table td {
                white-space: nowrap;
            }

            .btn {
                width: 100%;
                margin-top: 10px;
            }
        }
    </style>

    <div class="container">
        <h2 class="mb-4">Detail Perbaikan</h2>

        <div class="row mb-4 g-3">
            <!-- Data Pelapor -->
            <div class="col-md-6 col-12">
                <div class="card shadow-sm border-0 h-100 rounded-3">
                    <div class="card-header bg-primary text-white fw-bold">
                        <i class="bi bi-person-circle me-1"></i> Data Pelapor
                    </div>
                    <div class="card-body d-flex align-items-start info-flex">
                        <!-- Foto -->
                        <div class="me-3 flex-shrink-0">
                            <img src="{{ asset('storage/' . $pelapor->foto_identitas) }}" alt="Foto Identitas"
                                class="rounded shadow-sm border" style="width:150px; height:120px; object-fit:cover;">
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
                                    {{ $pelapor->alamat }}, Kel. {{ $pelapor->kelurahan->nama_kelurahan }},
                                    Kec. {{ $pelapor->kecamatan->nama_kecamatan }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Laporan -->
            <div class="col-md-6 col-12">
                <div class="card shadow-sm border-0 h-100 rounded-3">
                    <div class="card-header bg-success text-white fw-bold">
                        <i class="bi bi-file-earmark-text me-1"></i> Data Laporan
                    </div>
                    <div class="card-body d-flex align-items-start info-flex">
                        <!-- Foto Laporan -->
                        <div class="me-3 flex-shrink-0 text-center">
                            <img src="{{ asset('assets/global/img/laporan.png') }}" alt="Foto Laporan"
                                class="rounded shadow-sm border"
                                style="width:120px; height:100px; object-fit:cover; cursor:pointer;" data-bs-toggle="modal"
                                data-bs-target="#modalLaporan">
                            <div class="small text-muted mt-1">Lihat file</div>
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

        {{-- Data Perbaikan --}}
        <div class="card mt-3">
            <div class="card-header bg-info text-white">Data Perbaikan ( {{ ucwords($perbaikan->status_progress) }} )</div>
            <div class="card-body">
                <p><b>Keterangan:</b></p>
                <div class="border p-2 rounded">{{ $perbaikan->keterangan }}</div>

                <div class="d-flex flex-wrap gap-2 mt-3 justify-content-center justify-content-md-start">
                    @foreach ($perbaikan->dokPerbaikan as $dok)
                        <img src="{{ asset('storage/' . $dok->file_perbaikan) }}" alt="foto" width="150"
                            class="rounded shadow">
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Barang Digunakan --}}
        <div class="card mt-3">
            <div class="card-header bg-secondary text-white">Barang Digunakan</div>
            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Tanggal Opname</th>
                            <th>Jumlah</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($barang_digunakan as $barang)
                            <tr>
                                <td>{{ $barang->detailbarang->barang->nama_barang }} -
                                    {{ $barang->detailbarang->kode_barang }}</td>
                                <td>{{ longDateIndo($barang->tanggal_opname) }}</td>
                                <td>{{ $barang->jumlah_opname }}</td>
                                <td>{{ $barang->keterangan }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Barang Rusak --}}
        <div class="card mt-3">
            <div class="card-header bg-secondary text-white">Barang Rusak</div>
            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Tanggal Opname</th>
                            <th>Jumlah</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($barang_rusak as $barang)
                            <tr>
                                <td>{{ $barang->detailbarang->barang->nama_barang }} -
                                    {{ $barang->detailbarang->kode_barang }}</td>
                                <td>{{ longDateIndo($barang->tanggal_opname) }}</td>
                                <td>{{ $barang->jumlah_opname }}</td>
                                <td>{{ $barang->keterangan }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tombol --}}
        <div class="mt-3 d-flex justify-content-center justify-content-md-start">
            <a href="{{ url('admin/perbaikan') . '/' . $laporan->pelapor_id }}" class="btn btn-secondary">
                <i class="bi bi-skip-backward-fill me-2"></i> Kembali
            </a>
        </div>
    </div>

    @include('admin.perbaikan.modal')
@endsection
