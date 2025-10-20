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

            <form method="POST" action="{{ route('password.email') }}" class="p-3 mt-3">
                @csrf

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="form-field d-flex align-items-center mb-3">
                    <span class="far fa-envelope"></span>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                        placeholder="Email Address">
                </div>

                @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <div class="row mb-0">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary mt-3">
                            {{ __('Send Reset Link') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
