@extends('layouts.auth.main')

@section('title')
    Register
@endsection

@section('content')
    <div class="page page-center py-5">
        <div class="container container-tight">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="text-center mb-4">
                        <a href="{{ url('/') }}" aria-label="Home"
                            class="navbar-brand navbar-brand-autodark fw-bolder h3">
                            <img src="{{ asset('assets/global/img/logo.png') }}" alt="logo-app" style="width: 30px">
                            {{ config('app.name') }}
                        </a>
                    </div>

                    <div class="card card-md">
                        <div class="card-body">
                            <div class="py-3 py-md-0 px-3 px-md-0">
                                <h1 class="h1 text-start p-0 m-0 fw-bolder">Register</h1>
                                <p class="text-muted fw-light p-0 mb-4">untuk buat akun aplikasi ini.</p>
                            </div>
                            <form id="form-login" method="POST" action="{{ route('register') }}">
                                @csrf

                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">{{ __('Nama Lengkap') }}</label>
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name') }}" placeholder="Masukkan nama" autocomplete="name"
                                        autofocus>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="username" class="form-label">{{ __('Username') }}</label>
                                    <input id="username" type="text"
                                        class="form-control @error('username') is-invalid @enderror" name="username"
                                        value="{{ old('username') }}" placeholder="Masukkan username"
                                        autocomplete="username" autofocus>

                                    @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">{{ __('Alamat Email') }}</label>

                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" placeholder="Masukkan email" autocomplete="email">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="password" class="form-label">{{ __('Password') }}</label>

                                    <div class="input-group input-group-flat">
                                        <input type="password" id="create-password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            placeholder="Masukkan password" autocomplete="off">
                                        <span class="input-group-text">
                                            <a href="#" id="toggle-password" class="link-secondary"
                                                data-bs-toggle="tooltip" aria-label="Show password"
                                                data-bs-original-title="Show password">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </span>
                                    </div>

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="password-confirm"
                                        class="form-label">{{ __('Konfirmasi Password') }}</label>

                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" placeholder="Konfirmasi password anda"
                                        autocomplete="new-password">
                                </div>

                                <div class="form-footer">
                                    <button type="submit" class="btn btn-primary w-100">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </form>
                        </div>


                        <div class="hr-text">or</div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <a href="{{ url('auth/bintan-sso/login') }}" class="btn btn-4 w-100">
                                        <!-- Github icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon text-github icon-2">
                                            <path d="M9 19c-4.3 1.4 -4.3 -2.5 -6 -3..."></path>
                                        </svg>
                                        Register with Bintan SSO
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center text-secondary mt-3">
                        Sudah punya akun? <a href="{{ route('login') }}">Login sekarang</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function togglePassword() {
            const password = document.querySelector('input[name="password"]')
            const passwordConfrim = document.querySelector('input[name="password_confirmation"]')
            password.type = password.type === 'password' ? 'text' : 'password'
            passwordConfrim.type = passwordConfrim.type === 'password' ? 'text' : 'password'
        }

        $('#toggle-password').on('click', function(e) {
            e.preventDefault()
            togglePassword()
        })

        $('#form-login').submit(function(e) {
            e.preventDefault()

            startSpinSubmitBtn(`#form-login`)

            this.submit()
        })
    </script>
@endpush
