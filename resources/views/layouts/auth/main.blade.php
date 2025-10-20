<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>{{ config('app.name') }} - @yield('title')</title>
    <link rel="shortcut icon" href="{{ asset('assets/global/img/bintan.png') }}" type="image/x-icon">

    {!! renderSeoMetaTags() !!}

    <link rel="stylesheet" href="{{ asset('assets/admin/css/tabler.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/custom-style.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    {{-- <style>
        @import url("https://rsms.me/inter/inter.css");
    </style> --}}
</head>

<body>
    @yield('content')
</body>

{{-- Custom Components --}}
@include('components.submit-button-spinner')

{{-- Dependency JS --}}
<script src="{{ asset('assets/global/vendor/jquery@3.7.1/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/tabler.js') }}"></script>
<script src="{{ asset('assets/global/js/helpers/apiError.js') }}"></script>

{{-- Custom script --}}
@stack('script')
</body>

</html>
