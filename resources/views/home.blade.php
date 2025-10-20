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

        .hover-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            font-size: 0.85rem;
            /* kecilkan font global */
        }

        .hover-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.08);
        }

        .status-box {
            padding: 6px;
            border-radius: 8px;
            font-size: 0.75rem;
        }

        .bg-light-warning {
            background: #fff8e6;
        }

        .bg-light-primary {
            background: #e6f0ff;
        }

        .bg-light-success {
            background: #e6f9ed;
        }

        .bg-light-danger {
            background: #ffe6e6;
        }

        .status-box i {
            display: block;
            font-size: 1rem;
        }
    </style>
@endpush
@section('content')

    <div class="container-xl">
        <!-- Bagian Atas dengan Judul dan Deskripsi -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xxl-5 g-3">
            @foreach ($kecamatan as $item)
                <div class="col">
                    <div class="card shadow-sm border-0 rounded-4 h-100 hover-card">
                        <div class="card-body text-center p-3">
                            <h6 class="card-title mb-3 text-primary fw-bold">
                                <i class="bi bi-geo-alt-fill me-1"></i>{{ $item->nama_kecamatan }}
                            </h6>

                            <div class="row g-1">
                                <div class="col-6">
                                    <div class="status-box bg-light-warning">
                                        <i class="bi bi-hourglass-split text-warning small"></i>
                                        <div class="fw-bold text-dark small">
                                            {{ countLaporanByKecamatan($item->id_kecamatan, 'pending') ?? 0 }}
                                        </div>
                                        <small class="text-muted">Pending</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="status-box bg-light-primary">
                                        <i class="bi bi-gear-wide-connected text-primary small"></i>
                                        <div class="fw-bold text-dark small">
                                            {{ countLaporanByKecamatan($item->id_kecamatan, 'proses') ?? 0 }}
                                        </div>
                                        <small class="text-muted">Proses</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="status-box bg-light-success">
                                        <i class="bi bi-check-circle-fill text-success small"></i>
                                        <div class="fw-bold text-dark small">
                                            {{ countLaporanByKecamatan($item->id_kecamatan, 'selesai') ?? 0 }}
                                        </div>
                                        <small class="text-muted">Selesai</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="status-box bg-light-danger">
                                        <i class="bi bi-x-circle-fill text-danger small"></i>
                                        <div class="fw-bold text-dark small">
                                            {{ countLaporanByKecamatan($item->id_kecamatan, 'ditolak') ?? 0 }}
                                        </div>
                                        <small class="text-muted">Ditolak</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>


        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card border-0 shadow-md p-4"
                    style="background: linear-gradient(135deg, #ffffff, #045ee6); color: #78ecf6;">
                    <div class="card-body">
                        <!-- Section Header -->
                        {{-- <div class="row">
                            <div class="col-md-12 text-center py-3">
                                <img src="{{ asset('megat.svg') }}" class="img-fluid mb-2" style="width:400px;"
                                    alt="">
                            </div>
                        </div> --}}
                        <!-- Main Content -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <!-- Title -->
                                <h2 class="fw-bold mb-3" style="color: #5f20c5;">
                                    Apa itu SI PEJANTAN?
                                </h2>
                                <!-- Description -->
                                <p class="lead text-dark">
                                    <strong>SI PRASMANAN (Sistem Informasi Pengelolaan Penerangan Jalan Umum)</strong>
                                    aplikasi
                                    terpadu untuk pendataan, pemantauan, dan pengelolaan infrastruktur jalan. Sistem ini
                                    menggabungkan peta interaktif, basis data asset jalan (lampu, rambu, gorong-gorong,
                                    badan jalan, dll.), serta modul pelaporan kerusakan dan penjadwalan pemeliharaan
                                    sehingga proses inventarisasi, prioritisasi perbaikan, dan pengambilan keputusan menjadi
                                    lebih cepat dan berbasis data.
                                </p>
                                <a href="{{ url('pengaduan') }}" class="btn btn-outline-dark mt-3">Pengaduan Disini</a>
                            </div>
                            <div class="col-md-6 text-center">
                                <!-- Image -->
                                <img src="{{ asset('opd/img/pic2.png') }}" class="img-fluid mt-1 rounded"
                                    style="width: 420px;" alt="Gambar PIC">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="modal modal-blur fade" id="modal-cari-tiket" tabindex="-1" aria-labelledby="modalCreateLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCreateLabel">Cari Tiket Aduan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-cari" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            {{-- <label for="search-no_tiket">No Tiket</label> --}}
                            <input type="text" class="form-control" id="search-no_tiket" name="no_tiket"
                                placeholder="Masukkan no tiket aduan" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> --}}
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        // Menangani form submit untuk pencarian tiket
        $('#form-cari').on('submit', function(e) {
            e.preventDefault(); // Mencegah pengiriman form secara standar

            var noTiket = $('#search-no_tiket').val(); // Ambil nomor tiket dari input

            $.ajax({
                url: '{{ route('cari_pengaduan') }}', // URL sesuai dengan rute kamu
                type: 'GET',
                data: {
                    no_tiket: noTiket
                },
                success: function(response) {
                    if (response.status === 'success') {
                        // Tampilkan data tiket jika ditemukan
                        window.location.href = response.redirect_url;

                    }
                    showSwal(response.message, response.status)

                },
                error: function(xhr) {
                    if (xhr.status === 404) {
                        showSwal(xhr.message, xhr.status)
                    }
                }
            });
        });
    </script>
@endpush
