<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ 'Dishub Web Service' . (isset($title) ? " - $title" : '') }}</title>

    {{-- <!-- CSS files --> --}}
    <link href="{{ asset('assets/admin/css/tabler.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/tabler-flags.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('') }}opd/css/tabler-payments.min.css" rel="stylesheet" />
    <link href="{{ asset('') }}opd/css/tabler-vendors.min.css" rel="stylesheet" />
    <link href="{{ asset('') }}opd/css/demo.min.css" rel="stylesheet" />
    <link href="{{ asset('') }}opd/vendor/datatables.net-bs5/dataTables.bootstrap5.css" rel="stylesheet" />
    <link href="{{ asset('assets/global/vendor/select2@4.1.0-rc.0/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/global/vendor/select2-bootstrap-5-theme@1.3.0/select2-bootstrap-5-theme.min.css') }}">
    <link href="{{ asset('assets/global/vendor/sweetalert2@11.22.0/sweetalert2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/global/css/custom-style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('assets/global/css/leaflet.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/global/css/MarkerCluster.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/global/css/MarkerCluster.Default.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('') }}favicon.ico">


    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }

        .pre-footer {
            background-color: #f8f9fa;
            padding: 20px 0;
            border-top: 1px solid #ddd;
        }

        .pre-footer h5 {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .pre-footer p,
        .pre-footer ul {
            margin-bottom: 0;
        }

        .pre-footer ul {
            padding-left: 0;
        }

        .pre-footer ul li {
            list-style-type: none;
        }

        .pre-footer ul li a {
            color: #007bff;
            text-decoration: none;
        }

        .pre-footer ul li a:hover {
            text-decoration: underline;
        }

        .dropdown-menu {
            z-index: 1050;
        }
    </style>
    @stack('style')

</head>

<body>

    <div class="page">
        @include('layouts.out.navbar')

        <div class="page-wrapper">


            <div class="page-body">
                @yield('content')
            </div>
            <div class="pre-footer bg-light py-4">
                <div class="container-xl">
                    <div class="row">
                        <!-- Kolom Alamat -->

                        <div class="col-md-3">
                            <h5>Alamat</h5>
                            <p>
                                <strong>Dinas Perhubungan Kabupaten Bintan</strong><br>
                                Jalan Raya Tanjungpinang – Tanjung Uban KM.42
                            </p>
                        </div>
                        <div class="col-md-3">
                            <h5>Link Terkait</h5>
                            <!-- Tambahkan dua logo di bawah alamat -->
                            <div class="d-flex justify-content-start align-items-center">
                                <a href="https://bintankab.go.id/" class="p-2" target="_blank">
                                    <img src="{{ asset('opd/img/bintan.png') }}" alt="Logo 2"
                                        class="img-fluid rounded" style="width: 50px;">
                                </a>
                                <a href="https://diskominfo.bintankab.go.id/" target="_blank">
                                    <img src="{{ asset('opd/img/diskominfo.png') }}" class="p-2" alt="Logo 1"
                                        class="img-fluid me-3 rounded" style="width: 70px;">
                                </a>

                            </div>
                        </div>
                        <!-- Kolom Navigasi -->
                        <div class="col-md-3">
                            <h5>Navigasi</h5>
                            <ul class="list-unstyled">
                                <li><a href="javascript:void(0)" style="color: black">→ Home</a></li>
                                <li><a href="{{ url('opd/pengaduan') }}" style="color: black">→ Peta</a></li>
                                <li><a href="{{ url('opd/permohonan') }}" style="color: black">→ Pengaduan</a>
                                </li>
                                <li><a href="{{ url('login') }}" style="color: black">→ Login</a></li>
                            </ul>
                        </div>

                        <!-- Kolom Lokasi Kami -->
                        <div class="col-md-3">
                            <h5>Lokasi Kami</h5>
                            <div class="iframe-container">
                                <iframe
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.099990962387!2d104.4995622754474!3d1.087249962362658!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d964b73ece52d5%3A0xe8de58d44b77f77a!2sKantor%20Bupati%20Bintan!5e0!3m2!1sid!2sid!4v1729766188479!5m2!1sid!2sid"
                                    allowfullscreen="" loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>

                            <style>
                                .iframe-container {
                                    position: relative;
                                    width: 100%;
                                    height: 0;
                                    padding-bottom: 56.25%;
                                    /* Rasio aspek 16:9 (56.25%) */
                                    overflow: hidden;
                                }

                                .iframe-container iframe {
                                    position: absolute;
                                    top: 0;
                                    left: 0;
                                    width: 100%;
                                    height: 100%;
                                    border: 0;
                                }
                            </style>

                        </div>
                    </div>
                </div>
            </div>
            <footer class="footer footer-transparent d-print-none">
                <div class="container-xl">
                    <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                        <ul class="list-inline list-inline-dots mb-0">
                            <li class="list-inline-item">
                                Copyright &copy; {{ date('Y') }}
                                Tim IT Diskominfo Bintan
                            </li>
                        </ul>
                    </div>
                </div>
            </footer>
            @include('components.datatables-spinner')
            @include('components.submit-button-spinner')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('assets/admin/js/tabler.min.js') }}" defer></script>
    <script src="{{ asset('assets/global/vendor/jquery@3.7.1/jquery-3.7.1.min.js') }}"></script>

    {{-- <!-- Tabler Core --> --}}
    <script src="{{ asset('') }}opd/js/jquery.min.js"></script>
    <script src="{{ asset('') }}opd/js/tabler.min.js"></script>
    <script src="{{ asset('') }}opd/js/demo.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.3/jquery-ui.min.js"></script>
    {{-- Datatables --}}
    <script src="{{ asset('assets/global/vendor/datatables.net@2.0.8/dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/global/vendor/datatables.net-bs5/dataTables.bootstrap5.min.js') }}"></script>

    {{-- <!-- Libs JS --> --}}
    {{-- <script src="{{ asset('') }}opd/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="{{ asset('') }}opd/libs/jsvectormap/dist/js/jsvectormap.min.js"></script>
    <script src="{{ asset('') }}opd/libs/jsvectormap/dist/maps/world.js"></script>
    <script src="{{ asset('') }}opd/libs/jsvectormap/dist/maps/world-merc.js"></script> --}}

    {{-- Other Dependency --}}
    <script src="{{ asset('assets/global/js/helpers/datatables.js') }}"></script>
    <script src="{{ asset('assets/global/vendor/sweetalert2@11.22.0/sweetalert2.all.min.js') }}" defer></script>
    <script src="{{ asset('assets/global/js/helpers/alert.js') }}"></script>
    <script src="{{ asset('assets/global/js/helpers/global.js') }}"></script>
    <script src="{{ asset('assets/global/js/helpers/apiError.js') }}"></script>
    <script src="{{ asset('assets/global/vendor/leaflet.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>
    <script src="{{ asset('assets/global/vendor/select2@4.1.0-rc.0/select2.min.js') }}"></script>
    <script src="{{ asset('assets/global/vendor/select2@4.1.0-rc.0/id.js') }}"></script>
    {{-- Custom script --}}
    @stack('script')
</body>

</html>
