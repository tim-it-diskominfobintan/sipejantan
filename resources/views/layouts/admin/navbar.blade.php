<header class="navbar navbar-expand-md navbar-light bg-light d-print-none">
    <div class="container-xl">
        <!-- Button untuk navbar collapse -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
            aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Bagian User dan Profil -->
        <div class="navbar-nav flex-row order-md-last">
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                    aria-label="Open user menu">
                    <span class="rounded-circle avatar avatar-sm"
                        style="background-image: url({{ asset('admin/static/avatars/000m.jpg') }})"></span>
                    <div class="d-none d-xl-block ps-2">
                        <div>{{ auth()->user()->username }}</div>
                        <div class="mt-1 small text-secondary text-capitalize">{{ auth()->user()->role }}</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Navbar Menu yang akan Collapse -->
        <div class="collapse navbar-collapse" id="navbar-menu">
            <form action="./" method="get" autocomplete="off" novalidate="">
                <div class="input-icon">
                    <span class="input-icon-addon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                            <path d="M21 21l-6 -6"></path>
                        </svg>
                    </span>
                    <input type="text" value="" class="form-control" placeholder="Search…"
                        aria-label="Search in website">
                </div>
            </form>
        </div>
    </div>
</header>
