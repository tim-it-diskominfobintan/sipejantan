<header class="navbar navbar-expand-md navbar-overlap d-print-none" data-bs-theme="light">
    <div class="container-xl">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
            aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
            <a href="javascript:void(0)">
                <img src="{{ asset('assets/global/img/icon_topik.png') }}" width="200" height="50"
                    alt="LOGO DISINI" class="navbar-brand-image">
            </a>
        </h1>
        <div class="navbar-nav flex-row order-md-last">
            <!-- Existing Navbar Items (Themes, Notifications, etc.) -->

        </div>
        <!-- Navbar Menu -->
        <div class="collapse navbar-collapse" id="navbar-menu">
            <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
                <ul class="navbar-nav">
                    <!-- Dashboard Link -->

                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="currentColor"
                                    class="icon icon-tabler icons-tabler-filled icon-tabler-home">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M12.707 2.293l9 9c.63 .63 .184 1.707 -.707 1.707h-1v6a3 3 0 0 1 -3 3h-1v-7a3 3 0 0 0 -2.824 -2.995l-.176 -.005h-2a3 3 0 0 0 -3 3v7h-1a3 3 0 0 1 -3 -3v-6h-1c-.89 0 -1.337 -1.077 -.707 -1.707l9 -9a1 1 0 0 1 1.414 0m.293 11.707a1 1 0 0 1 1 1v7h-4v-7a1 1 0 0 1 .883 -.993l.117 -.007z" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Home
                            </span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"
                            data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-map">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 7l6 -3l6 3l6 -3v13l-6 3l-6 -3l-6 3v-13" />
                                    <path d="M9 4v13" />
                                    <path d="M15 7v13" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Peta
                            </span>
                        </a>
                        <div class="dropdown-menu">
                            @foreach ($jenis_asset as $item)
                                <a class="dropdown-item" href="{{ url('jenisAsset/' . $item->id_jenis_asset) }}">
                                    <img src="{{ asset('storage/' . $item->foto_jenis_asset) }}" class="avatar me-2"
                                        style="width: 20px; height: 20px; object-fit: cover;" />
                                    {{ $item->jenis_asset }}
                                </a>
                            @endforeach
                        </div>
                    </li>
                    <!-- Pengaduan Link -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('pengaduan') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                    <path d="M15 12l-3 -3l-3 3m0 3l3 3l3 -3" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Pengaduan
                            </span>
                        </a>
                        <div class="dropdown-menu">
                            @foreach ($jenis_asset as $item)
                                <a class="dropdown-item" href="{{ url('jenisAsset/' . $item->id_jenis_asset) }}">
                                    <img src="{{ asset('storage/' . $item->foto_jenis_asset) }}" class="avatar me-2"
                                        style="width: 20px; height: 20px; object-fit: cover;" />
                                    {{ $item->jenis_asset }}
                                </a>
                            @endforeach
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('informasi_tiket') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-file-isr">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M15 3v4a1 1 0 0 0 1 1h4" />
                                    <path d="M15 3v4a1 1 0 0 0 1 1h4" />
                                    <path d="M6 8v-3a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-7" />
                                    <path d="M3 15l3 -3l3 3" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Informasi Tiket
                            </span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"
                            data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-chart-histogram">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 3v18h18" />
                                    <path d="M20 18v3" />
                                    <path d="M16 16v5" />
                                    <path d="M12 13v8" />
                                    <path d="M8 16v5" />
                                    <path d="M3 11c6 0 5 -5 9 -5s3 5 9 5" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Grafik
                            </span>
                        </a>
                        <div class="dropdown-menu">
                            @foreach ($jenis_asset as $item)
                                <a class="dropdown-item" href="{{ url('grafik/' . $item->id_jenis_asset) }}">
                                    <img src="{{ asset('storage/' . $item->foto_jenis_asset) }}" class="avatar me-2"
                                        style="width: 20px; height: 20px; object-fit: cover;" />
                                    {{ $item->jenis_asset }}
                                </a>
                            @endforeach
                        </div>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</header>
