@extends('layouts.out.main')

@section('title', $title)
@push('style')
    <style>
        h1.display-5 {
            font-size: 2.5rem;
            font-weight: bold;
        }

        h2,
        h3 {
            margin-top: 10px;
            font-weight: normal;
        }

        .card {
            border-radius: 10px;
        }

        .card-title {
            font-weight: bold;
        }


        .pagination {
            justify-content: center;
            /* posisi tengah */
            margin-top: 15px;
        }

        .pagination .page-link {
            padding: 4px 10px;
            /* kecilkan padding */
            font-size: 0.8rem;
            /* kecilkan font */
            border-radius: 6px;
            /* rounded */
            color: #0d6efd;
            border: 1px solid #dee2e6;
            transition: all 0.2s ease-in-out;
        }

        .pagination .page-link:hover {
            background-color: #0d6efd;
            color: #fff;
        }

        .pagination .active .page-link {
            background-color: #0d6efd;
            color: #fff;
            border-color: #0d6efd;
        }

        .laporan-wrapper {
            background: linear-gradient(135deg, #ffffff, #f2f3f5);
        }

        .laporan-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .laporan-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        }

        .avatar-box {
            width: 60px;
            height: 60px;
            background: #f8f9fa;
        }

        .avatar-group {
            display: flex;
            align-items: center;
        }

        .avatar-group-item {
            margin-left: -8px;
            border: 2px solid #fff;
            border-radius: 10%;
            overflow: hidden;
            width: 50px;
            height: 50px;
            flex-shrink: 0;
        }

        .avatar-group-item:first-child {
            margin-left: 0;
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10%;
        }
    </style>
@endpush
@section('content')

    <div class="container-xl">
        <!-- Bagian Atas dengan Judul dan Deskripsi -->
        <div class="row mt-3">
            <div class="col-md-12">
                <form id="form-cari" class="mb-3" action="{{ route('guest.tiket') }}" method="GET">
                    <div class="input-group">
                        <input type="text" name="no_tiket" id="search-no_tiket" class="form-control"
                            placeholder="Cari tiket (No Laporan)" value="{{ request('no_tiket') }}">
                        <button class="btn btn-primary" type="submit">Cari</button>
                    </div>
                </form>

                {{-- <h2>Informasi Tiket</h2> --}}
                <div class="card border-0 shadow-sm rounded-4 p-0 laporan-wrapper">
                    <div class="card-body">
                        <div class="col-md-12">
                            <div class="space-y">
                                @forelse($laporan as $item)
                                    <div class="card mb-3 border-0 shadow-sm rounded-4 laporan-card">
                                        <div class="row g-0 align-items-start">
                                            <!-- Avatar Pelapor (di kiri pada md+, atas di mobile) -->
                                            <div class="col-12 col-md-auto text-center p-3">
                                                <div class="rounded-circle overflow-hidden border shadow-sm avatar-box mx-auto d-flex align-items-center justify-content-center bg-light"
                                                    style="width: 80px; height: 80px;">
                                                    <i class="bi bi-person fs-1 text-secondary"></i>
                                                </div>
                                            </div>

                                            <!-- Konten Laporan -->
                                            <div class="col-12 col-md">
                                                <div class="card-body ps-md-0">
                                                    <div
                                                        class="d-flex flex-column flex-md-row justify-content-between gap-3">
                                                        <div class="flex-fill">
                                                            <h5 class="fw-bold text-primary mb-1">
                                                                {{ $item->no_laporan }}
                                                            </h5>
                                                            <span class="text-secondary small d-block mb-1">
                                                                <u>Detail Laporan</u>
                                                            </span>

                                                            <div class="mb-1">
                                                                <span class="fw-semibold">Asset:</span>
                                                                {{ optional($item->asset)->nama_asset }}
                                                            </div>

                                                            <p class="mb-0 text-wrap">{{ $item->deskripsi_laporan }}</p>
                                                        </div>

                                                        <!-- FOTO DOKUMEN (tetap ada) -->
                                                        @php
                                                            $allDokumen = \App\Models\DokLaporan::where(
                                                                'laporan_id',
                                                                $item->id_laporan,
                                                            )->get();
                                                            $dokumenPerbaikan = \App\Models\DokPerbaikan::whereHas(
                                                                'perbaikan',
                                                                function ($q) use ($item) {
                                                                    $q->where('laporan_id', $item->id_laporan);
                                                                },
                                                            )->get();
                                                        @endphp

                                                        <div class="ms-md-3 mt-3 mt-md-0" style="min-width:120px;">
                                                            {{-- Dokumen Laporan --}}
                                                            @if ($allDokumen->count() > 0)
                                                                <div class="text-md-end text-start">
                                                                    <div class="small text-success">Dokumen laporan</div>
                                                                    <div
                                                                        class="avatar-group d-flex justify-content-md-end justify-content-start mt-1">
                                                                        @foreach ($allDokumen as $dok)
                                                                            <div class="avatar-group-item me-1"
                                                                                style="cursor:pointer;">
                                                                                <img src="{{ asset('storage/' . $dok->file_laporan) }}"
                                                                                    class="avatar-img" alt="Foto Laporan"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#fotoModal"
                                                                                    data-img="{{ asset('storage/' . $dok->file_laporan) }}">
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="text-md-end text-start small text-muted">Belum
                                                                    ada dokumen laporan</div>
                                                            @endif

                                                            {{-- Dokumen Perbaikan --}}
                                                            <div class="mt-2">
                                                                @if ($dokumenPerbaikan->count() > 0)
                                                                    <div class="text-md-end text-start">
                                                                        <div class="small text-success">Dokumen perbaikan
                                                                        </div>
                                                                        <div
                                                                            class="avatar-group d-flex justify-content-md-end justify-content-start mt-1">
                                                                            @foreach ($dokumenPerbaikan as $dok)
                                                                                <div class="avatar-group-item border border-success me-1"
                                                                                    style="cursor:pointer;">
                                                                                    <img src="{{ $dok->foto_perbaikan_url }}"
                                                                                        class="avatar-img"
                                                                                        alt="Foto Perbaikan"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#fotoModal"
                                                                                        data-img="{{ $dok->foto_perbaikan_url }}">
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div class="text-md-end text-start small text-muted">
                                                                        Belum ada dokumen perbaikan</div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Metadata bawah -->
                                                    <div class="mt-3 d-flex flex-wrap gap-2">
                                                        <span class="badge bg-light text-dark">👤 Anonimus</span>
                                                        <span class="badge bg-light text-dark">📅
                                                            {{ $item->created_at->format('d M Y H:i:s') }}</span>
                                                        <span
                                                            class="badge {{ $item->status_laporan === 'selesai' ? 'bg-success' : 'bg-warning text-dark' }}">
                                                            {{ ucfirst($item->status_laporan) }}
                                                        </span>

                                                        @if ($item->status_laporan === 'ditolak')
                                                            <span
                                                                class="badge bg-danger text-white">{{ $item->ket_tolak }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @empty
                                    <p class="text-muted">Laporan tidak ditemukan.</p>
                                @endforelse

                                <div class="mt-3 text-center">
                                    {{ $laporan->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="fotoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <img id="modalImage" src="" class="img-fluid rounded" alt="Foto Laporan">
                </div>
            </div>
        </div>
    </div>

    {{-- Modal untuk lihat semua foto --}}

@endsection
@push('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const modalImage = document.getElementById("modalImage");
            const fotoModal = document.getElementById("fotoModal");

            // event delegasi untuk semua gambar yg punya data-img
            document.body.addEventListener("click", function(e) {
                if (e.target.matches("[data-img]")) {
                    const imgSrc = e.target.getAttribute("data-img");
                    modalImage.src = imgSrc;
                }
            });
        });
    </script>
@endpush
