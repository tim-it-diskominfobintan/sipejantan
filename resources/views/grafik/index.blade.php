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

        .custom-cluster {
            background: transparent;
            border-radius: 50%;
            position: relative;
        }

        .cluster-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            position: relative;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .cluster-icon span {
            position: relative;
            z-index: 2;
        }

        .cluster-icon .pulse {
            position: absolute;
            top: -10px;
            left: -10px;
            width: 60px;
            height: 60px;
            border: 4px solid rgba(255, 0, 0, 0.4);
            border-radius: 50%;
            animation: pulseAnim 2s infinite;
            z-index: 1;
        }

        @keyframes pulseAnim {
            0% {
                transform: scale(0.8);
                opacity: 0.7;
            }

            70% {
                transform: scale(1.3);
                opacity: 0;
            }

            100% {
                opacity: 0;
            }
        }
    </style>
@endpush
@section('content')
    <div class="container-xl">
        <!-- Bagian Atas dengan Judul dan Deskripsi -->
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card-body">

                    <div class="col-md-12 text-center">
                        <h1 class="display-5">Grafik Asset</h1>
                        <h3 class="mt-3">Dinas Perhubungan Kabupaten Bintan</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-12 mt-4">
                <div class="card p-3">
                    <div class="container">
                        <div class="row g-4 align-items-center">

                            <div class="col-md-3 text-center">
                                <div class="card d-flex align-items-center justify-content-center">
                                    <div style="font-size:80px;">
                                        <img src="{{ asset('storage/' . $jenisAsset->foto_jenis_asset) }}" alt="">
                                    </div>
                                    <h5>Jumlah Total {{ $jenisAsset->jenis_asset }}</h5>
                                    <h1 id="totalPju">{{ $totalPju }}</h1>
                                </div>
                            </div>

                            <!-- Pie Chart -->
                            <div class="col-md-3">
                                <div class="card d-flex align-items-center justify-content-center" style="height:300px;">
                                    <h6 class="text-center">Kondisi {{ $jenisAsset->jenis_asset }}</h6>
                                    <canvas id="donutChart"></canvas>
                                </div>
                            </div>

                            <!-- Bar Chart -->
                            <div class="col-md-6">
                                <div class="card d-flex align-items-center justify-content-center" style="height:400px;">
                                    <h6 class="text-center">Jumlah dan Kondisi {{ $jenisAsset->jenis_asset }} Per Kecamatan
                                    </h6>
                                    <canvas id="barChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(document.getElementById("donutChart"), {
            type: 'doughnut',
            data: {
                labels: ['Baik', 'Rusak Ringan', 'Rusak Berat'],
                datasets: [{
                    data: [
                        {{ $kondisiCounts->baik }},
                        {{ $kondisiCounts->rusak_ringan }},
                        {{ $kondisiCounts->rusak_berat }}
                    ],
                    backgroundColor: ['#2CA58D', '#F5A623', '#E74C3C']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Ambil data dari controller
        const kecamatanData = @json($kecamatanData);

        // Ambil label (nama kecamatan) dan data kondisi
        const kecamatanLabels = kecamatanData.map(item => item.nama_kecamatan);
        const baikData = kecamatanData.map(item => item.baik);
        const rusakRinganData = kecamatanData.map(item => item.rusak_ringan);
        const rusakBeratData = kecamatanData.map(item => item.rusak_berat);

        // Bar Chart (Stacked)
        new Chart(document.getElementById("barChart"), {
            type: 'bar',
            data: {
                labels: kecamatanLabels,
                datasets: [{
                        label: 'Baik',
                        data: baikData,
                        backgroundColor: '#2CA58D'
                    },
                    {
                        label: 'Rusak Ringan',
                        data: rusakRinganData,
                        backgroundColor: '#F5A623'
                    },
                    {
                        label: 'Rusak Berat',
                        data: rusakBeratData,
                        backgroundColor: '#E74C3C'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    x: {
                        stacked: true
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endpush
