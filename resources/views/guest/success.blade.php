@extends('layouts.out.main')

@section('title', 'Pengaduan Berhasil')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                        <h3 class="mt-3 fw-bold">Pengaduan Berhasil Dikirim</h3>
                        <p class="text-muted">Terima kasih, laporan Anda sudah masuk ke sistem kami.</p>

                        <div class="alert alert-info mt-3">
                            <h5 class="mb-1">Nomor Laporan Anda</h5>
                            <h4 class="fw-bold text-primary">{{ $no_laporan }}</h4>
                            <small>Simpan nomor laporan ini untuk pengecekan status selanjutnya.</small>

                            <div class="">
                                <button class="btn btn-sm btn-outline-primary" onclick="copyNoLaporan()">
                                    <i class="bi bi-copy me-1"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ url('/') }}" class="btn btn-outline-secondary me-2">
                                <i class="bi bi-house me-2"></i> Home
                            </a>
                            <a href="{{ url('/informasi_tiket') }}" class="btn btn-outline-info me-2">
                                <i class="bi bi-search me-2"></i> Cari Tiket
                            </a>
                            <a href="{{ url('/pengaduan') }}" class="btn  btn-outline-success">
                                <i class="bi bi-plus-circle me-2"></i> Buat Pengaduan Baru
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        function copyNoLaporan() {
            const noLaporan = '{{ $no_laporan }}';
            navigator.clipboard.writeText(noLaporan).then(() => {
                // alert('Nomor laporan berhasil disalin ke clipboard!');
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Nomor laporan berhasil disalin!",
                    showConfirmButton: false,
                    timer: 1500
                });
            }).catch(err => {
                console.error('Gagal menyalin nomor laporan: ', err);
            });
        }
    </script>
@endpush
