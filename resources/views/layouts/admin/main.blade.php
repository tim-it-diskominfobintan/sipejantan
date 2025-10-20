<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? config('app.name') . " - $title" : '' }}</title>

    {{-- Theme dependencies --}}
    <link href="{{ asset('assets/admin/css/tabler.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/tabler-flags.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/tabler-vendors.min.css') }}" rel="stylesheet" />

    {{-- Custom CSS --}}
    <link href="{{ asset('assets/global/css/custom-style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/custom-style.css') }}" rel="stylesheet">

    {{-- Vendor Dependencies --}}
    <link href="{{ asset('assets/global/vendor/select2@4.1.0-rc.0/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/global/vendor/select2-bootstrap-5-theme@1.3.0/select2-bootstrap-5-theme.min.css') }}"
        rel="stylesheet" />
    <link href="{{ asset('assets/global/vendor/datatables.net-bs5/dataTables.bootstrap5.min.css') }}"
        rel="stylesheet" />
    <link href="{{ asset('assets/global/vendor/sweetalert2@11.22.0/sweetalert2.min.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet" />
    <link href="{{ asset('assets/global/img/favicon/favicon.ico') }}" type="image/x-icon" rel="icon" />

    <link rel="stylesheet" href="{{ asset('assets/global/css/leaflet.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/global/css/MarkerCluster.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/global/css/MarkerCluster.Default.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />

    {{-- Font Dependencies --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">

    <style>
        @import url('https://rsms.me/inter/inter.css');

        /* :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        } */

        /* body {
            font-feature-settings: "cv03", "cv04", "cv11";
        } */
    </style>

    @stack('style')
</head>

<body>
    <div class="page">

        @include('layouts.admin.sidebar')

        {{-- @include('layouts.admin.navbar') --}}

        <div class="page-wrapper">
            <div class="page-header d-print-none px-5">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <h1 class="page-title">
                                {{ $title }}
                            </h1>
                            <h5 class="page-pretitle mt-1">
                                {{ $subtitle }}
                            </h5>
                        </div>

                        @yield('action-header')
                    </div>
                </div>
            </div>

            <div class="page-body px-5">
                @yield('content')
            </div>

            <footer class="footer footer-transparent d-print-none">
                <div class="container-xl px-5">
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
        </div>
    </div>

    @include('components.datatables-spinner')
    @include('components.submit-button-spinner')

    {{-- Theme Dependencies --}}
    <script src="{{ asset('assets/admin/js/tabler.min.js') }}" defer></script>

    {{-- Vendor Dependencies --}}
    <script src="{{ asset('assets/global/vendor/jquery@3.7.1/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/global/vendor/datatables.net@2.0.8/dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/global/vendor/datatables.net-bs5/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/global/vendor/select2@4.1.0-rc.0/select2.min.js') }}"></script>
    <script src="{{ asset('assets/global/vendor/select2@4.1.0-rc.0/id.js') }}"></script>
    <script src="{{ asset('assets/global/vendor/sweetalert2@11.22.0/sweetalert2.all.min.js') }}" defer></script>
    <script src="{{ asset('assets/global/js/helpers/datatables.js') }}"></script>
    <script src="{{ asset('assets/global/js/helpers/alert.js') }}"></script>
    <script src="{{ asset('assets/global/js/helpers/global.js') }}"></script>
    <script src="{{ asset('assets/global/js/helpers/apiError.js') }}"></script>
    <script src="{{ asset('assets/global/vendor/leaflet.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    {{-- Custom script --}}
    @stack('script')
</body>

</html>
