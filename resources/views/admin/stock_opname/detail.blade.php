@extends('layouts.admin.main')

@section('content')
    <div class="container py-3">
        <h2 class="mb-4 fw-semibold text-center text-md-start">Detail Perbaikan</h2>

        <div class="row g-3 mb-4">
            <!-- Data Pelapor -->
            <div class="col-12 col-md-6">
                <div class="card shadow-sm border-0 h-100 rounded-3">
                    <div class="card-header bg-primary text-white fw-bold">
                        <i class="bi bi-person-circle me-1"></i> Data Pelapor
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column flex-sm-row align-items-start gap-3">
                            <!-- Foto -->
                            <div class="flex-shrink-0 text-center">
                                <img src="{{ asset('storage/' . $pelapor->foto_identitas) }}" alt="Foto Identitas"
                                    class="rounded shadow-sm border" style="width:120px; height:100px; object-fit:cover;">
                            </div>
                            <!-- Detail -->
                            <div class="w-100 small">
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
                                    <div class="col-8 text-break">{{ $pelapor->email }}</div>
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
            </div>

            <!-- Data Laporan -->
            <div class="col-12 col-md-6">
                <div class="card shadow-sm border-0 h-100 rounded-3">
                    <div class="card-header bg-success text-white fw-bold">
                        <i class="bi bi-file-earmark-text me-1"></i> Data Laporan
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column flex-sm-row align-items-start gap-3">
                            <!-- Foto Laporan -->
                            <div class="text-center">
                                <img src="{{ asset('assets/global/img/laporan.png') }}" alt="Foto Laporan"
                                    class="rounded shadow-sm border"
                                    style="width:100px; height:80px; object-fit:cover; cursor:pointer;"
                                    data-bs-toggle="modal" data-bs-target="#modalLaporan">
                                <div class="small text-muted mt-1">Lihat file</div>
                            </div>
                            <!-- Detail -->
                            <div class="w-100 small">
                                <div class="row mb-2">
                                    <div class="col-4 fw-bold text-muted">No Laporan</div>
                                    <div class="col-8">
                                        <span class="badge bg-info text-white">{{ $laporan->no_laporan }}</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4 fw-bold text-muted">Status</div>
                                    <div class="col-8">
                                        <span
                                            class="badge 
                                        @if ($laporan->status_laporan == 'pending') bg-danger
                                        @elseif($laporan->status_laporan == 'proses') bg-primary
                                        @elseif($laporan->status_laporan == 'diterima') bg-warning
                                        @else bg-success @endif text-white">
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
                                    <div class="col-8 text-secondary">{{ $laporan->deskripsi_laporan }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Data Perbaikan --}}
        <div class="card mt-3">
            <div class="card-header bg-info text-white">Data Perbaikan ({{ ucwords($perbaikan->status_progress) }})</div>
            <div class="card-body small">
                <p><b>Keterangan:</b></p>
                <div class="border p-2 rounded">{{ $perbaikan->keterangan }}</div>

                <div class="d-flex flex-wrap gap-2 mt-3">
                    @foreach ($perbaikan->dokPerbaikan as $dok)
                        <img src="{{ asset('storage/' . $dok->file_perbaikan) }}" alt="foto" width="120"
                            class="rounded shadow-sm border">
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Barang Digunakan --}}
        <div class="card mt-3">
            <div class="card-header bg-secondary text-white">Barang Digunakan</div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-sm align-middle text-nowrap">
                    <thead class="table-light">
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
            <div class="card-header bg-danger text-white">Barang Rusak</div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-sm align-middle text-nowrap">
                    <thead class="table-light">
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
        <div class="text-center text-md-start mt-3">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </div>

    @include('admin.perbaikan.modal')
@endsection
