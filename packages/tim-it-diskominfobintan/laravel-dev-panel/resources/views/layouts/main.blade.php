<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} - Dev Panel</title>

     {{-- Vendor Dependencies --}}
    <link href="{{ asset('vendor/laravel-dev-panel/vendor/bootstrap@5.3.7/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/laravel-dev-panel/vendor/select2@4.1.0-rc.0/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/laravel-dev-panel/vendor/select2-bootstrap-5-theme@1.3.0/select2-bootstrap-5-theme.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/laravel-dev-panel/vendor/datatables.net-bs5/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/laravel-dev-panel/vendor/sweetalert2@11.22.0/sweetalert2.min.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"  rel="stylesheet" />
    {{-- <link href="{{ asset('vendor/laravel-dev-panel/img/favicon/favicon.ico') }}" type="image/x-icon" rel="icon" /> --}}
    
    <script src="{{ route('dev-panel.assets', ['file' => 'global.js']) }}"></script>

    <style>
        * {
            font-family: system-ui, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
        }

        .card {
            border-radius: 12px;
        }

        .card .card-header,
        .card .card-footer {
            background: none !important;
        }

        input:read-only {
            filter: brightness(95%) !important;
        }

        #table-activity_wrapper .row.mt-2.justify-content-between {
            padding-left: 20px;
            padding-right: 20px;
        }
    </style>
</head>

    @yield('content')

    @include('dev-panel::activity.modal')
    @include('dev-panel::components.datatables-spinner')

    {{-- Vendor Dependencies --}}
    <script src="{{ asset('vendor/laravel-dev-panel/vendor/bootstrap@5.3.7/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendor/laravel-dev-panel/vendor/jquery@3.7.1/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('vendor/laravel-dev-panel/vendor/datatables.net@2.0.8/dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/laravel-dev-panel/vendor/datatables.net-bs5/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('vendor/laravel-dev-panel/vendor/select2@4.1.0-rc.0/select2.min.js') }}"></script>
    <script src="{{ asset('vendor/laravel-dev-panel/vendor/select2@4.1.0-rc.0/id.js') }}"></script>
    <script src="{{ asset('vendor/laravel-dev-panel/vendor/sweetalert2@11.22.0/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('vendor/laravel-dev-panel/alert.js') }}"></script>
    <script src="{{ asset('vendor/laravel-dev-panel/global.js') }}"></script>
    <script src="{{ asset('vendor/laravel-dev-panel/datatables.js') }}"></script>

    {{-- Custom script --}}
    @stack('script')

    </body>

</html>


