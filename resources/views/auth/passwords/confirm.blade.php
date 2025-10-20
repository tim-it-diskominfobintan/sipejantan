@extends('layouts.auth')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="logo">
                <img src="{{ asset('images/logo/bintan.png') }}" alt="">
            </div>
            <div class="text-center mt-4 name">
                {{ config('app.name', 'SSO') }}
            </div>

            <form method="POST" action="{{ route('password.confirm') }}" class="p-3 mt-3">
                @csrf

                <div class="form-field d-flex align-items-center mb-3">
                    <span class="fas fa-key"></span>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" required autocomplete="current-password" placeholder="Enter Your Password">
                </div>

                @error('password')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <div class="row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary mt-3">
                            {{ __('Confirm Password') }}
                        </button>

                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
